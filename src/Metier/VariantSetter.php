<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 01/11/19
 * Time: 13:51
 */

namespace App\Metier;


class VariantSetter
{

    public static function set( $variant ) {

        $data_json = json_encode( $variant );
        $url = sprintf('https://%s:%s@sneakers-heat.myshopify.com/admin/api/2019-10/variants/%s.json',$_ENV['SHOPIFY_LOGIN'], $_ENV['SHOPIFY_PWD'], $variant['variant']['id']);
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_json)));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
