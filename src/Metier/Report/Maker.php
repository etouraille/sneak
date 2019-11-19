<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 01/11/19
 * Time: 11:25
 */

namespace App\Metier\Report;


use Psr\Log\LoggerInterface;

class Maker
{

    private $lines = [];
    private $logger;

    public function __construct(LoggerInterface $appLogger ) {
        $this->logger = $appLogger;
    }

    public function addLine( $line, $exception = false ) {
        $this->lines[] = $line;
        $this->logger->info($line);
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
