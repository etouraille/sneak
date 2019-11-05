<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 01/11/19
 * Time: 09:04
 */

namespace App\Metier;


use App\Entity\Mapping;
use App\Metier\Report\Maker;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class DifftoPrice
{

    private $em;
    private $report;
    private $converter;
    private $integrity;
    private $logger;
    private $priceSetter;

    public function __construct(EntityManagerInterface $em , Maker $report, LoggerInterface $logger ) {
        $this->em = $em;
        $this->report = $report;
        $this->converter = new DollardToEuro( $report );
        $this->integrity = new IntegrityChecker( $report );
        $this->priceSetter = new SetNewPrice( $report , $logger );
        $this->logger = $logger;
    }

    public function make( $diff ) {
        $mapping = $this->em->getRepository(Mapping::class)->find($diff['idMatch']);
        if(0 === count($diff["sizePrice"])) {
            // abort
            $this->report->addLine(sprintf("Le produit %s n'est pas traité : problème lors du mapping sur stockx", $mapping->getShopifyUrl()), true  );
        }
        $md5 = Hash::make($diff['sizePrice']);
        if($md5 !== $mapping->getHashOldPriceAndSize()) {
            // update price
            $productAndVariant = $diff['productAndVariant'];
            $sizeAndPrice = $diff['sizePrice'];
            $error = [];
            $this->setNewPrice($sizeAndPrice , $productAndVariant, $error);
            $countNotIsset = 0;
            $updated = 0;

            foreach( $productAndVariant['variants'] as $i => $variant ) {
                if(isset( $variant['dollard' ])) {
                    $productAndVariant['variants'][$i]['newPrice'] = Rate::apply( $this->converter->toEuro($variant['dollard']));
                    $updated ++;
                } else {
                    $countNotIsset ++;
                }
            }
            if(count($productAndVariant['variants']) > 0 && count( $productAndVariant['variants']) === $countNotIsset ) {
                $this->report->addLine(sprintf('Aucun nouveau prix pour l url %s', $mapping->getShopifyUrl()), true );
            }
            if(count( $productAndVariant['variants']) === 0 ) {
                $this->report->addLine(sprintf("Aucun variant pour %s", $productAndVariant['handle']), true );
            }
            $this->integrity->check($productAndVariant);
            $this->priceSetter->set($productAndVariant);
            $this->report->addLine(sprintf("Mise a jour de %s prix pour %s", $updated, $productAndVariant['handle']));
            $mapping->setHashOldPriceAndSize( $md5 );
            $this->em->merge($mapping);
            $this->em->flush();

        } else {
            $this->report->addLine(sprintf("Le produit %s n'est pas traité, pas de variation de prix constatée", $mapping->getShopifyUrl()));
        }
    }

    private function setNewPrice( $sizeAndPrice , &$productAndVariant , &$error ) {
        foreach( $productAndVariant['variants'] as $i => $variant ) {
                $title = $variant['title'];
                $break = false;
                foreach ($sizeAndPrice as $sp) {
                    $size = $sp['size'];
                    if ( $this->sizeEqualTitle( $size, $title) ) {
                        $break = true;
                        break;
                    }

                }
                if( $break ) {
                    $productAndVariant['variants'][$i]['dollard'] = $sp['price'];
                }
                else {
                    $this->report->addLine(sprintf("Le variant %s dans %s n'a pas de correspondant sur stockx", $variant['id'], $productAndVariant['handle']));
                }
            }
    }

    private function sizeEqualTitle( $size, $title ) {
        // size : US 12
        if(!preg_match('/([0-9\.,;]*)/i', $size , $match )) {
            throw new \Exception( 'Problème de match 1');
        }
        $size = $match[1];

        // title : 12 US - 11.5 UK - 46 2/3 FR
        if( !preg_match('/([^ ]*)( |)US/i', $title, $match )) {
            throw new \Exception( 'Problème de match 2');
        }
        $title = $match[1];


        return (float) $size == ( float ) $title;
    }
}
