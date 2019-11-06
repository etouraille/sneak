<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 06/11/19
 * Time: 10:57
 */

namespace App\Utils;


class Counter
{
    private $file;
    private $data;

    public function __construct() {
        $this->file = __DIR__.'/count.txt';
        try {
            $this->data = (int) file_get_contents($this->file);
        } catch(\Exception $e ) {
            file_put_contents($this->file, '0');
            $this->data  = 0;
        }
    }

    public function read() {
        return (int) file_get_contents($this->file);
    }

    public function increment() {
        $n = $this->read();
        $n ++;
        file_put_contents($this->file, $n."");
    }

    public function init() {
        file_put_contents($this->file , '0');
    }
}
