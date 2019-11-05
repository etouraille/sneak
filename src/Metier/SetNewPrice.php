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

                $response = VariantSetter::set([
                    'variant' => [
                        'id' => $variant['id'],
                        'price' => $variant['newPrice']
                    ]
                ]);
                var_dump( $response );
                $this->logger->info(sprintf ("For %s : Response %s", $productAndVariant['handle'], $response));
                $this->report->addLine(sprintf('Mise à jour pour %s,%s de %s Euros à %s Euros', $productAndVariant['handle'], $variant['title'], $variant['price'], $variant['newPrice']));
            }
        }
        $this->report->addLine(sprintf("Mise a jour des prix du produit %s pur une %s en moyenne de %s %%", $productAndVariant['handle'], $delta > 0? 'augmentation' : 'diminution', $delta / $n ));
        return null;
    }
}
