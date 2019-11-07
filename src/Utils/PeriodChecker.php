<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 07/11/19
 * Time: 11:25
 */

namespace App\Utils;

class PeriodChecker
{

    public static function check( $res ) {

        for($T=1;$T<=count($res);$T++) {
            $v0 = [];
            $t = 0;
            foreach( $res as $id ) {
                $t++;
                $v0[] = $id;
                if($t === $T ) {
                    break;
                }
            }
            $periodic = true;
            foreach( $res as $index => $id ) {
                $p = $index % $T;
                // is T vaut 2
                // index = 0 , $p = 0;
                // index = 1 , $p = 1
                // index = 2, $p = 0;
                if( $id !== $v0[$p]) {
                    $periodic = false;
                    break;
                }

            }
            if( $periodic ) break;
        }
        return $T < count( $res )?(int)( count($res)/ count($v0)):false;
    }

    public static function period( $res ) {

        for($T=1;$T<=count($res);$T++) {
            $v0 = [];
            $t = 0;
            foreach( $res as $id ) {
                $t++;
                $v0[] = $id;
                if($t === $T ) {
                    break;
                }
            }
            $periodic = true;
            foreach( $res as $index => $id ) {
                $p = $index % $T;
                // is T vaut 2
                // index = 0 , $p = 0;
                // index = 1 , $p = 1
                // index = 2, $p = 0;
                if( $id !== $v0[$p]) {
                    $periodic = false;
                    break;
                }

            }
            if( $periodic ) break;
        }
        return $T < count( $res )? $v0 : false;
    }
}
