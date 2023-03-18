<?php

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;

class ApiModel
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function fetchLatestExchangeRates()
    {
        // Fetch data from API
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.exchangerate.host/latest');
        $data = $response->getContent();

        // Process data
        $data = json_decode($data, true);
        $rates = $data['rates'];

        $date = $data['date']; //Gets date from the API
        $timestamp = date('Y-m-d', strtotime($date)); //Converts date to string in date sql format

        //creating connection
        $conn = $this->entityManager->getConnection();

        //Getting the latest time from the database
        $stmt = $conn->prepare('SELECT MAX(timestamp) as latest_timestamp FROM exchange_rate');
        $resultSet = $stmt->execute();
        $result = $resultSet->fetchAssociative();
        $latestTimestamp = $result['latest_timestamp'];

        // Insert or update data in the database
        if (isset($rates) && is_array($rates)) {
            
            foreach ($rates as $currency => $rate) {
                if ($currency === 'EUR' || $currency === 'USD' || $currency === 'TRY'){
                    $stmt = $conn->prepare('INSERT INTO exchange_rate (currency, rate, timestamp) VALUES (:currency, :rate, :timestamp)');
                    $stmt->bindValue('currency', $currency);
                    $stmt->bindValue('rate', round($rate, 2));
                    $stmt->bindValue('timestamp',  $timestamp);
                    $stmt->execute();
                }
              
            }
        }
    }
    public function UpdateLatestExchangeRates(){
               // Fetch data from API
               $client = HttpClient::create();
               $response = $client->request('GET', 'https://api.exchangerate.host/latest');
               $data = $response->getContent();
       
               // Process data
               $data = json_decode($data, true);
               $rates = $data['rates'];
       
               $date = $data['date']; //Gets date from the API
               $timestamp = date('Y-m-d', strtotime($date)); //Converts date to string in date sql format
       
               //creating connection
               $conn = $this->entityManager->getConnection();
       
               //Getting the latest time from the database
               $stmt = $conn->prepare('SELECT MAX(timestamp) as latest_timestamp FROM exchange_rate');
               $resultSet = $stmt->execute();
               $result = $resultSet->fetchAssociative();
               $latestTimestamp = $result['latest_timestamp'];

               if (isset($rates) && is_array($rates)) {
                foreach ($rates as $currency => $rate) {
                    if ($currency === 'EUR' || $currency === 'USD' || $currency === 'TRY'){
                        $stmt = $conn->prepare('UPDATE exchange_rate SET rate = :rate, timestamp = :timestamp WHERE currency = :currency');
                        $stmt->bindValue('currency', $currency);
                        $stmt->bindValue('rate', round($rate, 2));
                        $stmt->bindValue('timestamp',  $timestamp);
                        $stmt->execute();

                        if($latestTimestamp != $timestamp){
                        $stmt = $conn->prepare('INSERT INTO rate_history (currency, rate, timestamp) VALUES (:currency, :rate, :timestamp)');
                        $stmt->bindValue('currency', $currency);
                        $stmt->bindValue('rate', round($rate, 2));
                        $stmt->bindValue('timestamp',  $timestamp);
                        $stmt->execute();
                        }

                        if($latestTimestamp === $timestamp){
                        $stmt = $conn->prepare('UPDATE rate_history SET rate = :rate, timestamp = :timestamp WHERE (currency = :currency AND timestamp =:timestamp)');
                        $stmt->bindValue('currency', $currency);
                        $stmt->bindValue('rate', round($rate, 2));
                        $stmt->bindValue('timestamp',  $timestamp);
                        $stmt->execute();
                        }
                        

                    }
                        
                    
                }

            }
    }

    public function getCurrencyNames()
    {
        $conn = $this->entityManager->getConnection();

        $sql = 'SELECT currency FROM exchange_rate';
        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->execute();
        $currencyNames = $resultSet->fetchAll(\PDO::FETCH_COLUMN);

        return $currencyNames;
    }

    public function selectAllData()
    {
        $conn = $this->entityManager->getConnection();

        $sql = 'SELECT * FROM exchange_rate';
        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->execute();
        $allData = $resultSet->fetchAll(\PDO::FETCH_ASSOC);

        return $allData;
    }

    public function selectAllHistory()
    {
        $conn = $this->entityManager->getConnection();

        $sql = 'SELECT * FROM rate_history';
        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->execute();
        $allData = $resultSet->fetchAll(\PDO::FETCH_ASSOC);

        return $allData;
    }
}
