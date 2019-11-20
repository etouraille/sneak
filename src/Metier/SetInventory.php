<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 20/11/19
 * Time: 09:48
 */

namespace App\Metier;


use Curl\Curl;

class SetInventory
{



    public static function null( $variantId )
    {

        $curl = new Curl();
        $curl->get(sprintf('https://%s:%s@sneakers-heat.myshopify.com/admin/api/2019-10/variants/%s.json', $_ENV['SHOPIFY_LOGIN'], $_ENV['SHOPIFY_PWD'], $variantId));
        $data = json_decode($curl->response, true);
        $inventory_item_id = $data['variant']['inventory_item_id'];
        $quantity = $data['variant']['inventory_quantity'];
        $curl->get(sprintf('https://%s:%s@sneakers-heat.myshopify.com/admin/api/2019-10/locations.json', $_ENV['SHOPIFY_LOGIN'], $_ENV['SHOPIFY_PWD']));
        $data = json_decode( $curl->response, true);


        $location_id = $data['locations'][0]['id'];
        $data_json = json_encode( ['location_id' => $location_id, 'inventory_item_id' => $inventory_item_id, 'available_adjustment'  => -1 * $quantity] );
        $url = sprintf('https://%s:%s@sneakers-heat.myshopify.com//admin/api/2019-10/inventory_levels/adjust.json',$_ENV['SHOPIFY_LOGIN'], $_ENV['SHOPIFY_PWD']);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_json)));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;

    }
}
