<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 31/10/19
 * Time: 10:35
 */

namespace App\Command;

use App\Metier\DifftoPrice;
use App\Metier\MappingToDiff;
use App\Metier\ProductParser;
use App\Metier\Report\Maker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Backup extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'back';
    private $em;


    public function __construct( EntityManagerInterface $em ) {
        $this->em = $em;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $url = sprintf('https://%s:%s@sneakers-heat.myshopify.com/admin/api/2019-10/products.json', $_ENV['SHOPIFY_LOGIN'], $_ENV['SHOPIFY_PWD']);

        $date = new \DateTime();

        $this->next( $url, $date );

    }

    private function next( $url , $date ) {
        $curl = new \Curl\Curl;
        $curl->get( $url );
        $data = $curl->response;
        $backup = new \App\Entity\Backup();
        $backup->setDate( $date );
        $backup->setData( $data );
        $this->em->persist( $backup );
        $this->em->flush();

        foreach($curl->response_headers as $header ) {
            if( preg_match('/<https:\/\/([a-zA-Z&0-9\.\?_\/\-=]*?)>; rel="next"/', $header, $match ) ) {
                $url = sprintf('https://%s:%s@%s', $_ENV['SHOPIFY_LOGIN'], $_ENV['SHOPIFY_PWD'], $match[1]);
                $this->next($url, $date );
            }
        }

    }
}
