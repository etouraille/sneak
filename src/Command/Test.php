<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 31/10/19
 * Time: 10:35
 */

namespace App\Command;

use App\Entity\Mapping;
use App\Metier\Proxy\FreshFactory;
use App\Metier\Proxy\Load;
use App\Metier\SizeAndPrice\Parser;
use App\Metier\VariantSetter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Test extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'test';
    private $em;


    public function __construct( EntityManagerInterface $em ) {
        $this->em = $em;
        parent::__construct();
    }

    protected function configure()
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // test du script de backup.
        $mappings = $this->em->getRepository(Mapping::class )->findAll();
        $backs = $this->em->getRepository(\App\Entity\Backup::class)->findAll();
        $first = [];
        foreach( $mappings as $index => $mapping ) {
            if($index < 20 ) {
                $first[] = $mapping;
            }
        }

        $p = [];


        $datas = [];
        foreach($backs as $back ) {
            $datas[] = $back->getData();
        }

        foreach( $first as $mapping ) {
            $url = $mapping->getShopifyUrl();
            preg_match('/\/([^\/]*)$/', $url , $match );
            foreach($datas as $data ) {
                $data = json_decode($data, true );
                foreach( $data['products'] as $product )
                {
                    if( $product['handle'] === $match[1]) {
                        $p[] = $product;
                    }
                }

            }
        }

        foreach( $p as $product ) {
            foreach( $product['variants'] as $variant ) {
                /*
                $response = VariantSetter::set([
                    'variant' => [
                        'id' => $variant['id'],
                        'price'=> $variant['price']
                    ]
                ]);

                var_dump($response);
                */
            }
        }



    }
}
