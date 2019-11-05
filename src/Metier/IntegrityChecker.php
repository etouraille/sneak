<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 01/11/19
 * Time: 12:33
 */

namespace App\Metier;


use App\Metier\Report\Maker;

class IntegrityChecker
{

    public function __construct( Maker $report ) {
        $this->report = $report;
    }

    public function check($productAndVariant) {
        $mean = 0;
        $n = 0;
        foreach($productAndVariant['variants'] as $variant ) {
            if(isset( $variant['newPrice'])) {
                $mean += $delta = $this->delta($variant['price'], $variant['newPrice']);
                $n++;
            }
        }
        $mean = $mean / $n;

        if($mean >= 0.2) $this->report->addLine(sprintf("La variation moyenne du prix est superieur à 20 %% pour %s", $productAndVariant['handle']));
        if($mean >= 1) $this->report->addLine(sprintf("La variation moyenne du prix est superieur à 100 %% pour %s", $productAndVariant['handle']), true );
    }

    private function delta( $price1, $price2 ) {
        return 2 * abs((float )$price1 - (float )$price2) / ( (float)$price1 + (float) $price2 );
    }
}
