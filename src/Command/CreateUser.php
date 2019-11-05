<?php
/**
 * Created by PhpStorm.
 * User: etouraille
 * Date: 31/10/19
 * Time: 10:35
 */

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateUser extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'create-user';
    protected $em;
    protected $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $em ) {
        $this->encoder = $encoder;
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a new user.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...')
            ->addArgument('email',  InputArgument::REQUIRED , 'User email')
            ->addArgument('password',  InputArgument::REQUIRED , 'User password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
            $user = new User();
            $user->setEmail($input->getArgument('email'));
            $encoded = $this->encoder->encodePassword( $user, $input->getArgument('password'));
            $user->setPassword($encoded);
            $this->em->persist( $user );
            $this->em->flush();
    }
}
