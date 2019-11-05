<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 01/11/19
 * Time: 11:25
 */

namespace App\Metier\Report;


class Maker
{

    private $lines = [];

    public function __construct() {

    }

    public function addLine( $line, $exception = false ) {
        $this->lines[] = $line;
        echo $line . "\n";
        if( $exception ) {
            throw new \Exception(sprintf('Error %s', $line ));
        }
    }

    public function exportHTML() {
        $str = '';
        foreach($this->lines as $line ) {
            $str.= $line . '<br />';
        }
        return $str;
    }

    public function export() {
        $str = '';
        foreach($this->lines as $line ) {
            $str.= $line . "\n";
        }
        return $str;
    }

}
