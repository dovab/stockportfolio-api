<?php

namespace App\Entity;

use App\Repository\StockPriceRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockPriceRepository::class)]
class StockPrice
{
    private const PRICE_MULTIPLIER = 10000; // We want to support up to 4 decimals for the price

    #[ORM\Id()]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[ORM\GeneratedValue(strategy: 'UUID')]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Stock::class)]
    private Stock $stock;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    private DateTime $date;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $price;  // We store the price as an integer (price * PRICE_MULTIPLIER)

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return StockPrice
     */
    public function setId(string $id): StockPrice
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Stock
     */
    public function getStock(): Stock
    {
        return $this->stock;
    }

    /**
     * @param Stock $stock
     *
     * @return StockPrice
     */
    public function setStock(Stock $stock): StockPrice
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     *
     * @return StockPrice
     */
    public function setDate(DateTime $date): StockPrice
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price / self::PRICE_MULTIPLIER;
    }

    /**
     * @param int $price
     *
     * @return StockPrice
     */
    public function setPrice(int $price): StockPrice
    {
        $this->price = round($price * self::PRICE_MULTIPLIER);

        return $this;
    }
}