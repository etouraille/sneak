<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 02/11/19
 * Time: 11:16
 */

namespace App\Metier\SizeAndPrice;


use App\Entity\Mapping;
use App\Entity\SizePrice;
use App\Metier\Proxy\FreshFactory;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class Parser implements \Iterator
{

    private $mappings;
    private $current;
    private $proxy;
    private $factory;

    public function __construct( EntityManagerInterface $em ) {

        $this->mappings = $em->getRepository(Mapping::class)->findAll();
        $this->current = 0;
        $this->factory = new FreshFactory( $em );
        $this->proxy = $this->factory->getFreshProxy();


    }

    public function current() {

        sleep(rand(3,5));

        $client = new Client(
            [
                'proxy' => [
                    'http' => $this->proxy->getHost().':'.$this->proxy->getPort(),
                ],
                'cookies' => true,
            ]);

        //$client = \JonnyW\PhantomJs\Client::getInstance();
        //$client->setPhantomJs('/usr/local/bin/phantomjs');
        //$client->getEngine()->addOption(sprintf("--proxy=%s:%s", $this->proxy->getHost(), $this->proxy->getPort()));


        //$request = $client->getMessageFactory()->createRequest('GET', $this->mappings[$this->current]->getStockxUrl() );
        //$response = $client->getMessageFactory()->createResponse();
        //$client->send($request, $response);
        //$data = $response->getContent();

        //$crawler = new Crawler($data);
        $crawler = $client->request('GET',  $this->mappings[$this->current]->getStockxUrl());
        //var_dump($crawler);
        //$cookieJar = $client->getConfig()->cookies();
        //var_dump($cookieJar->toArray());
        $ret = [];
        $self = $this;
        $crawler->filter('#market-summary > div.options > div > div > div.select-options > div:nth-child(2) > ul > li.select-option > div')->each(function( $node) use ( &$ret , $self ) {
            if(!preg_match('/^(.*)\$(.*)$/',$node->text(), $match )) {

            } else {
                $sp = new SizePrice();
                $sp->setSize( $match[1]);
                $sp->setPrice( $match[2]);
                $sp->setMapping( $self->mappings[$this->current]);
                $ret[] = $sp;
                var_dump($sp->getSize());
            }
        });
        return $ret;
    }

    public function key()
    {
        return $this->current;

        // TODO: Implement key() method.
    }

    public function next()
    {
        // TODO: Implement next() method.

        ++ $this->current;
        $this->proxy = $this->factory->getFreshProxy();
    }

    public function rewind()
    {
        $this->position = 0;
        // TODO: Implement rewind() method.
    }

    public function valid()
    {
        // TODO: Implement valid() method.
        return isset($this->mappings[$this->current]);
    }
}
