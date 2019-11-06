<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 06/11/19
 * Time: 10:50
 */

namespace App\Utils;


class Dollard
{
    public static function removeComa( string $dollard ) {
        return (float) str_replace(',', '', $dollard );
    }
}
