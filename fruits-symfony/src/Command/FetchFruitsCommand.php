<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\HttpClient\HttpClient;
use App\Entity\Fruit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class FetchFruitsCommand extends Command
{

    private $entityManager;
    private $smtp;
    private $fromEmail;
    private $toEmail;

    public function __construct(EntityManagerInterface $entityManager, $smtp, $fromEmail, $toEmail)
    {
        $this->entityManager = $entityManager;
        $this->smtp = $smtp;
        $this->fromEmail = $fromEmail;
        $this->toEmail = $toEmail;
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
        $this->setName('fruits:fetch')
            ->setDescription('This command fetches fruits from fruityvice.com and save in local DB')
            ->setHelp('Run this command to fetch fruits from fruityvice.com and save in local DB.');
    }

    /**
     * 
     * Fetch fruits from https://fruityvice.com/
     * 
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $output->writeln('Connecting fruityvice.com');

        // Connect fruityvice.com api to retrieve data
        $httpClient = HttpClient::create();
        $response = $httpClient->request(
            'GET',
            'https://fruityvice.com/api/fruit/all'
        );

        $statusCode = $response->getStatusCode();

        // Check if connection is successful
        if($statusCode == 200){

            $output->writeln('Connection Successful');
            $output->writeln('Fetching Fruits...');

            $responseData = $response->toArray();

            $output->writeln('Checking for new records');

            $recordsCreated = 0;
            foreach($responseData as $data){
                
                // check if fruit with genus and name already exists
                $record = $this->entityManager->getRepository(Fruit::class)->findBy(['genus' => $data['genus'], 'name' => $data['name']] );

                if(!$record){

                    $fruit = new Fruit();
                    $fruit->setGenus($data['genus']);
                    $fruit->setName($data['name']);
                    $fruit->setFamily($data['family']);
                    $fruit->setFruitOrder($data['order']);
                    $fruit->setCarbohydrates($data['nutritions']['carbohydrates']);
                    $fruit->setProtein($data['nutritions']['protein']);
                    $fruit->setFat($data['nutritions']['fat']);
                    $fruit->setCalories($data['nutritions']['calories']);
                    $fruit->setSugar($data['nutritions']['sugar']);

                    $this->entityManager->persist($fruit);

                    $this->entityManager->flush();

                    $recordsCreated = $recordsCreated +1;
                }

            }

            if($recordsCreated > 0){
                $transport = Transport::fromDsn($this->smtp);
                $mailer = new Mailer($transport);

                $email = (new \Symfony\Component\Mime\Email())
                    ->from($this->fromEmail)
                    ->to($this->toEmail)
                    ->subject("New fruits records fetched")
                    ->html($recordsCreated." new fruits record(s) created")
                    ->text('Plain text email');

                /**
                 * @var \Symfony\Component\Mailer\MailerInterface $mailer
                 */
                $mailer->send($email);
            }

            $output->writeln('Records Added '.$recordsCreated);
        }else{
            $output->writeln('Connection Failed with status '.$statusCode);
        }
        

        return Command::SUCCESS;

    }

}