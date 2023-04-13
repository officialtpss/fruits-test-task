<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class GenerateUserCommand extends Command
{

    private $entityManager;
    private $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager,  UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
        parent::__construct();
    }


    /**
     * 
     * In this function set the name, description and help hint for the command
     * 
     */
    protected function configure(): void
    {
        // Use in-build functions to set name, description and help
        $this->setName('generate:user')
            ->setDescription('This command generate dummy user')
            ->setHelp('Run this command generate dummy user');
    }

    /**
     * 
     * Generate User
     * 
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $output->writeln('Generating User');

        $user = new User();
        $user->setEmail('user@mail.com');
        $user->setUsername('user@mail.com');
        $plaintextPassword = 12345678;
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
       
        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return Command::SUCCESS;

    }

}