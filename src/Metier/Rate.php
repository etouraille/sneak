<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 01/11/19
 * Time: 09:51
 */

namespace App\Metier;


class Rate
{
    public static function apply( $value ) {
        //(x + 35% + 15) + 26%
        $x = 1.35 * $value + 15;
        $y = 1.26 * $x;
        $ret = $y;
        $ret = round( $ret, 2);
        return $ret;
    }
}
