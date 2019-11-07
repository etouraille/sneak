<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 07/11/19
 * Time: 12:29
 */

namespace App\Metier;


use App\Entity\Mapping;
use App\Entity\Redo;
use App\Utils\PeriodChecker;
use Doctrine\ORM\EntityManagerInterface;

class RedoEnd
{

    private $em;

    public function __construct(EntityManagerInterface $em, \Swift_Mailer $mailer ) {
        $this->em = $em;
        $this->mailer = $mailer;
    }

    public function check( $redo ) {

        $periodicData = [];
        $redos = $this->em->getRepository(Redo::class )->findForPeriodChecker( $redo );
        foreach( $redos as $redo ) {
            $periodicData[] = $redo->getMappingId();
        }

        $n = PeriodChecker::check( $periodicData );
        if( $n && $n > 5 ) {
            $pendings = PeriodChecker::period( $periodicData );
            $content = "Apres le passage du cron, Les produits suivants sont restés en exception : <br/>";
            foreach( $pendings as $id ) {
                $mapping  = $this->em->getRepository(Mapping::class )->find( $id );
                $content .= sprintf("%s %s<br/>", $mapping->getShopifyUrl(), $mapping->getStockxUrl());
            }
            //'snkrsheat@gmail.com'
            $message = (new \Swift_Message('Rapport des produits restés en exception'))
                ->setFrom('report@sneaker.com')
                ->setTo(['edouard.touraille@gmail.com'])
                ->setBody(
                    $content,
                    'text/html'
                )
            ;
            // on n'envoie le mail que lors du premier lancement.
            $this->mailer->send($message);

            return true;
        } else {
            return false;
        }
    }
}
