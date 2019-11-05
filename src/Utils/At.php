<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 04/11/19
 * Time: 15:57
 */

namespace App\Utils;


class At
{

    protected $file;
    protected $time;

    public function __construct( $command, $time = "now + 1 min" ) {
    //ex /src/bin/console run --redo=hash
        $handle = fopen( $this->file = __DIR__.'/at.sh', 'w');
        fputs($handle , $command);
        fclose($handle);
        $this->time = $time;
    }

    public function run() {
        exec( sprintf('at -f %s %s', $this->file , $this->time ), $ret );
        dump( $ret );
    }
}
