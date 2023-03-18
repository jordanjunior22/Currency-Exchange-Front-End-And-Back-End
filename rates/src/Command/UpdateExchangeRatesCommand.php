<?php
namespace App\Command;

use App\Model\ApiModel;
use App\Controller\ApiController_test;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateExchangeRatesCommand extends Command
{
    private $entityManager;
    private $apiModel;

    public function __construct(EntityManagerInterface $entityManager, ApiModel $apiModel)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->apiModel = $apiModel;
    }

    protected function configure()
    {
        $this->setName('app:update-exchange-rates')
             ->setDescription('Updates the exchange rates from the API every hour');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        while (true) {
            $this->apiModel->UpdateLatestExchangeRates();
            sleep(3600); // Sleep for 1 hour
        }
    }
}
