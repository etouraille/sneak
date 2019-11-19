<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 19/11/19
 * Time: 15:10
 */

namespace App\Utils;


class Replace
{

    public static function replace( $string ) {
        $string = str_replace("\n", '', $string);
        $string = str_replace("\t", '', $string);
        $string = str_replace("\r", '', $string);
        $string = str_replace(' ', '', $string);
        $string = str_replace(" ", '', $string );
        return $string;
    }
}
