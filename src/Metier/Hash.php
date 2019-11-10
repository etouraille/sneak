<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 01/11/19
 * Time: 08:33
 */

namespace App\Metier;


class Hash
{

    public static function make( $productAndVariant ) {
        $str = '';
        foreach( $productAndVariant['variants'] as $variant ) {
            if(isset($variant['newPrice'])) $str.= (string ) $variant['newPrice'];
        }
        return md5($str);
    }

}

