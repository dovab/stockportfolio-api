<?php

namespace App\Application\Service;

use App\Entity\StockPrice;
use App\Repository\StockRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Scheb\YahooFinanceApi\ApiClientFactory;

class StockService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private StockRepository $stockRepository
    ) {}

    /**
     * Updates the prices for all stocks in the database
     */
    public function updatePrices(): void
    {
        $client = ApiClientFactory::createApiClient();
        $stocks = $this->stockRepository->findAll();
        foreach($stocks as $stock) {
            $quote = $client->getQuote($stock->getTicker());
            if (null === $quote) {
                continue;
            }

            // Update the name
            $stock->setCompanyName($quote->getLongName());

            // Add the latest price
            $stockPrice = new StockPrice();
            $stockPrice->setPrice($quote->getRegularMarketPreviousClose());
            $stockPrice->setStock($stock);
            $stockPrice->setDate(new DateTime());
            $this->entityManager->persist($stockPrice);
        }

        $this->entityManager->flush();
    }
}