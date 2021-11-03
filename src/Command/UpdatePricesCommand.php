<?php

namespace App\Command;

use App\Application\Service\StockService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdatePricesCommand extends Command
{
    protected static $defaultName = 'app:update-prices';

    /**
     * @param StockService $stockService
     * @param string|null $name
     */
    public function __construct(private StockService $stockService, string $name = null)
    {
        parent::__construct($name);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->stockService->updatePrices();

        return Command::SUCCESS;
    }
}