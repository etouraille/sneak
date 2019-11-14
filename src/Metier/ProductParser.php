<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 31/10/19
 * Time: 18:33
 */

namespace App\Metier;


use App\Metier\Report\Maker;
use Curl\Curl;

class ProductParser
{

    private $data = [];
    private $report;

    public function __construct(Maker $report) {
        $this->report = $report;


    }

    public function getProductAndVariant( $handle ) {
        $ret = null;
        $n = 0;
        $break =false;
        $curl = new Curl();
        $curl->get(sprintf('https://%s:%s@sneakers-heat.myshopify.com/admin/api/2019-10/products.json?handle=%s', $_ENV['SHOPIFY_LOGIN'], $_ENV['SHOPIFY_PWD'], $handle));
        $data = json_decode($curl->response, true);
        if ($curl->error) {
            $this->report->addLine(sprintf('Problème lors du parsage des produits de shopify error curl : %s', $curl->error_code), true);
        }
        foreach($data['products'] as $product ) {
            $n++;
            if(strtolower($product['handle']) === strtolower($handle) ) {

                $break = true;
                break;
            }
        }
        if( !$break) {
            $this->report->addLine(sprintf("Le produit %s n'a pas de handle", $handle));
            return $ret;
        }
        $ret = ['id' => $product['id'], 'handle' => $handle, 'variants' => []];
        foreach( $product['variants'] as $variant ) {
            $ret['variants'][] = ['id' => $variant['id'], 'title' => $variant['title'], 'price' => $variant['price']];
        }
        return $ret;
    }
}
