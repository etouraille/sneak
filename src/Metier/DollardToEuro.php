<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 01/11/19
 * Time: 09:46
 */

namespace App\Metier;


use App\Command\LoadProxy;
use App\Metier\Report\Maker;

class DollardToEuro
{

    private $taux;

    public function __construct( Maker $report ) {
        $curl = new \Curl\Curl();
        $curl->get('https://api.exchangeratesapi.io/latest');
        if($curl->error) {
            $report->addLine(sprintf('Problème lors de la récupération du taux de conversion %s', $curl->error_code), true );
        }
        $data = json_decode( $curl->response , true );
        $this->taux = $data['rates']['USD'];

    }

    public function toEuro( $dollar ) {


        return $dollar / $this->taux;
    }
}
