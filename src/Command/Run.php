<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 31/10/19
 * Time: 10:35
 */

namespace App\Command;

use App\Entity\Redo;
use App\Metier\DifftoPrice;
use App\Metier\MappingToDiff;
use App\Metier\ProductParser;
use App\Metier\Report\Maker;
use App\Utils\At;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Run extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'run';
    private $em;
    private $report;
    private $mailer;
    private $logger;


    public function __construct( EntityManagerInterface $em, \Swift_Mailer $mailer, LoggerInterface $shopifyLogger  ) {
        $this->em = $em;
        $this->report = new Maker();
        $this->mailer = $mailer;
        $this->logger = $shopifyLogger;
        parent::__construct();
    }

    protected function configure() {
        $this
            ->addArgument('stockx', InputArgument::OPTIONAL, 'stockxUrl')
            ->addOption(
                'redo',
                null,
                InputOption::VALUE_OPTIONAL,
                'Redo hash',
                1
            );
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $t0 = time();
        $mapping = new MappingToDiff( $this->em , $this->report );
        $diffToPrice = new DifftoPrice($this->em , $this->report , $this->logger );

        $stockUrl = $input->getArgument('stockx');
        $redo = $input->getOption('redo');
        if(isset($redo) && is_string($redo)) {
            $mapping = new MappingToDiff( $this->em , $this->report, $redo  );
        } else {

            $mapping = new MappingToDiff( $this->em , $this->report );
        }

        $redoHash = (isset( $redo ) && is_string( $redo ))? $redo : md5('redo'.time());
        $abort = [];

        if( isset( $stockUrl ) ) {

            $diff = $mapping->one($stockUrl);
            $diffToPrice->make($diff);

        } else {


            foreach ( $mapping as $diff) {
                try {

                    $diffToPrice->make($diff);
                    $redos = $this->em->getRepository(Redo::class)->findByMappingId($diff['idMatch']);
                    foreach( $redos as $redo ) {
                        $this->em->remove( $redo );
                    }
                    $this->em->flush();
                } catch (\Exception $e) {
                    $abort[] = $diff['idMatch'];
                    $this->report->addLine(sprintf("Abort %s for reason %s", $diff['productAndVariant']['handle'], $e->getMessage()));
                    $entity = new Redo();
                    $entity->setBatch($redoHash);
                    $entity->setMappingId($diff['idMatch']);
                    $this->em->persist($entity);
                    $this->em->flush();
                }
            }
        }
        $t1 = time();
        $this->report->addLine(sprintf("Mise à jour réalisée en %s secondes", $t1-$t0));

        if(1 === $input->getOption('redo')) {

            $message = (new \Swift_Message('Rapport de mise à jour des prix'))
            ->setFrom('report@sneaker.com')
            ->setTo(['edouard.touraille@gmail.com', 'snkrsheat@gmail.com'])
            ->setBody(
                $this->report->exportHTML(),
                'text/html'
                )

            // you can remove the following code if you don't define a text version for your emails
            ->addPart(
                $this->report->export(),
                'text/plain'
                )
            ;
            // on n'envoie le mail que lors du premier lancement.
            $this->mailer->send($message);
        }
        if(count($abort) > 0 ) {
            $at = new At(sprintf("/src/bin/console run --redo=%s", $redoHash), "now + 10 min");
            $at->run();
        }

    }
}
