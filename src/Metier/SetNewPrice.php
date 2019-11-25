<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 01/11/19
 * Time: 10:09
 */

namespace App\Metier;



use App\Metier\Report\Maker;
use Psr\Log\LoggerInterface;

class SetNewPrice
{

    private $report;
    private $logger;

    public function __construct( Maker $report, LoggerInterface $logger ) {
        $this->report = $report;
        $this->logger = $logger;
    }

    public function set( $productAndVariant ) {

        $delta = 0;
        $n = 0;

        foreach( $productAndVariant['variants'] as $variant ) {

            if( isset($variant['newPrice'])) {

                $delta = (float) $variant['newPrice'] - (float) $variant['price'];
                $n ++;
                $res = [
                    'variant' => [
                        'id' => $variant['id'],
                        'price' => $variant['newPrice']
                    ]
                ];
                if(isset($variant['inventory_quantity'])) {
                    $res['variant']['inventory_quantity'] = $variant['inventory_quantity'];
                }
                $response = VariantSetter::set($res);
                $data = json_decode($response , true );
                if(!isset($data['variant'])) {
                    $this->logger->error(sprintf("Problème d'écriture sur stockx : %s", $response), []);
                    $this->report->addLine(sprintf("Problème décriture du prix %s", $response), true );
                }
                $this->report->addLine(sprintf('Mise à jour pour %s,%s de %s Euros à %s Euros', $productAndVariant['handle'], $variant['title'], $variant['price'], $variant['newPrice']));
            }
        }
        $this->report->addLine(sprintf("Mise a jour des prix du produit %s pur une %s en moyenne de %s %%", $productAndVariant['handle'], $delta > 0? 'augmentation' : 'diminution', $delta / $n ));
        return null;
    }
}
