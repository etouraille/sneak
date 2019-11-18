<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 31/10/19
 * Time: 10:35
 */

namespace App\Command;

use App\Entity\Mapping;
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

class BackupUrl extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'back:url';
    private $em;


    public function __construct( EntityManagerInterface $em ) {
        $this->em = $em;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $this->back();

    }

    private function back() {
        $handle = fopen(__DIR__.'/backup.csv', 'w+');
        $mappings = $this->em->getRepository(Mapping::class)->findAll();
        $res = [];
        foreach( $mappings as $mapping ) {
            $res[] = [$mapping->getShopifyUrl(), $mapping->getStockxUrl()];
        }
        foreach($res as $row)
        {
            fputcsv($handle, $row );

        }
        fclose( $handle );
    }
}
