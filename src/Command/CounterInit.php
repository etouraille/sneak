<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 31/10/19
 * Time: 10:35
 */

namespace App\Command;

use App\Utils\Counter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CounterInit extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'counter:init';
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
        $counter = new Counter();
        $counter->init();
    }
}
