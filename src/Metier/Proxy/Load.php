<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 02/11/19
 * Time: 10:21
 */

namespace App\Metier\Proxy;


use App\Entity\Proxy;
use Curl\Curl;
use Doctrine\ORM\EntityManagerInterface;

class Load
{

    private $curl;
    private $em;
    public function __construct(EntityManagerInterface $em ) {
        $this->curl = new Curl();
        $this->em = $em;
    }

    public function load() {
        $this->curl->get(sprintf('http://list.didsoft.com/get?email=%s&pass=%s&pid=httppremium&showcountry=yes&country=FR', $_ENV['DIDSOFT_LOGIN'],$_ENV['DIDSOFT_PASS']));
        if($this->curl->error ) {
            return false;
        }
        $data = $this->curl->response;
        preg_match_all('/(.*):(.*)#(.*)/', $data, $matches);
        $added = 0;
        foreach( $matches[1] as $index => $host ) {
            $proxy = $this->em->getRepository(Proxy::class)->findOneByHost( $host );
            if( ! $proxy ) {
                $added ++;
                $proxy = new Proxy();
                $proxy->setHost($host);
                $proxy->setPort($matches[2][$index]);
                $this->em->persist( $proxy );
                $this->em->flush();
            }
        }

        print sprintf("%s proxy ajoutés \n", $added);

    }
}
