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

    public static function make( $sizeAndPrice ) {
        $str = '';
        foreach( $sizeAndPrice as $sizePrice ) {
            $str.= $sizePrice['size'].$sizePrice['price'];
        }
        return md5($str);
    }

}

