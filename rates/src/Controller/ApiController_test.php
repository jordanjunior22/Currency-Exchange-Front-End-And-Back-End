<?php

namespace App\Controller;

use App\Model\ApiModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ApiController_test extends AbstractController
{
    /**
     * @Route("/fetchdata", name="fetchdata")
     */
    public function fetchData(EntityManagerInterface $entityManager): Response
    {
        // Instantiate ApiModel
        $apiModel = new ApiModel($entityManager);

        // Fetch data from API and insert in database
        $data = $apiModel->fetchLatestExchangeRates();
        // Return response
        return $this->json([
            'message' => 'Data saved to database!',
        ]);
    }

    /**
     * @Route("/update_data", name="update_data")
     */
    public function updateData(EntityManagerInterface $entityManager): Response
    {
        // Instantiate ApiModel
        $apiModel = new ApiModel($entityManager);

        // Fetch latest data from API and update
        
            $data = $apiModel->UpdateLatestExchangeRates();
            // Return response
            return $this->json([
                'message' => 'Data updated in database!',
            ]);
     
       
    }

    /**
     * @Route("/fetch_currencyname", name="fetch_currencyname")
     */
    public function getCurrencyNames(EntityManagerInterface $entityManager): Response
    {
        // Instantiate ApiModel
        $apiModel = new ApiModel($entityManager);

        // Fetch currency names from database
        $currencyNames = $apiModel->getCurrencyNames();

        // Return response
        return $this->json($currencyNames);
    }

    
    /**
     * @Route("/alldata", name="alldata")
     */
    public function GetAllData(EntityManagerInterface $entityManager): Response
    {
        // Instantiate ApiModel
        $apiModel = new ApiModel($entityManager);

        // Fetch currency names from database
        $alldata = $apiModel->selectAllData();

        // Return response
        return $this->json($alldata);
    }

        /**
     * @Route("/allhistory", name="allhistory")
     */
    public function allHistory(EntityManagerInterface $entityManager): Response
    {
        // Instantiate ApiModel
        $apiModel = new ApiModel($entityManager);

        // Fetch currency names from database
        $alldata = $apiModel->selectAllHistory();

        // Return response
        return $this->json($alldata);
    }
}
