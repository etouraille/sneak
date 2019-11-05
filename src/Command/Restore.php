<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 31/10/19
 * Time: 10:35
 */

namespace App\Command;

use App\Metier\VariantSetter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Restore extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'restore';
    private $em;


    public function __construct( EntityManagerInterface $em ) {
        $this->em = $em;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $backups = $this->em->getRepository(\App\Entity\Backup::class)->getOldest();
        foreach( $backups as $backup ) {
            $this->save( $backup );
        }
    }

    private function save( $backup ) {
        $data = json_decode($backup->getData(), true );
        foreach($data['products'] as $product ) {
            foreach( $product['variants'] as $variant ) {
                sleep(5);

                /*
                 $response = VariantSetter::set(['variant' => [
                    'id' => $variant['id'],
                    'price' => $variant['price']
                ]]);
                var_dump( $response );
                */
            }
        }
    }
}
