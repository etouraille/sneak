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

class CounterTest extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'counter:test';
    private $em;
    private $mailer;


    public function __construct( EntityManagerInterface $em ,\Swift_Mailer $mailer ) {
        $this->em = $em;
        $this->mailer = $mailer;
        parent::__construct();
    }

    protected function configure()
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $counter = new Counter();
        $n = $counter->read();
        $use = $n>0?round( $n / (150000 ) * 100, 2 ):0;
        $content = sprintf("À mis-parcours mensuel, sneakers-heat est à %s %% de son utilisation de crawlera.", $use );

        $message = (new \Swift_Message("Rapport Sneakers-Heat d'utilisation de Crawlera"))
            ->setFrom('report@sneakers-heat.com')
            ->setTo('edouard.touraille@gmail.com', 'snkrsheat@gmail.com')
            ->setBody(
                $content,
                'text/html'
            )

            // you can remove the following code if you don't define a text version for your emails
            ->addPart(
                $content,
                'text/plain'
            )
        ;
        // on n'envoie le mail que lors du premier lancement.
        $this->mailer->send($message);
    }
}
