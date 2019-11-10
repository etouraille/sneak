<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 31/10/19
 * Time: 10:35
 */

namespace App\Command;

use App\Entity\Mapping;
use App\Metier\PeriodChecker;
use App\Metier\Proxy\FreshFactory;
use App\Metier\Proxy\Load;
use App\Metier\SizeAndPrice\Parser;
use App\Metier\VariantSetter;
use App\Utils\Counter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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
        $this
            ->addOption(
                'no-mail',
                null,
                InputOption::VALUE_OPTIONAL,
                'Redo hash',
                1
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        var_dump( $input->getOption('no-mail'));

    }
}
