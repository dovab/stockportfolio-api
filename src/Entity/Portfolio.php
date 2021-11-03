<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Application\Interface\HasUserInterface;
use App\Infrastructure\Doctrine\Trait\SoftDeletableTrait;
use App\Infrastructure\Doctrine\Trait\TimestampableTrait;
use App\Repository\PortfolioRepository;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PortfolioRepository::class)]
#[ApiResource()]
class Portfolio implements HasUserInterface
{
    use TimestampableTrait, SoftDeletableTrait;

    private const PRICE_MULTIPLIER = 10000; // We want to support up to 4 decimals for the price and the amount

    #[ORM\Id()]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[ORM\GeneratedValue(strategy: 'UUID')]
    #[Groups(['stock:read'])]
    private string $id;

    #[ORM\ManyToOne(targetEntity: Stock::class)]
    private Stock $stock;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User|null $user = null;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $amount;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: false)]
    private DateTime $purchaseDate;

    #[ORM\Column(type: Types::INTEGER, nullable: false)]
    private int $purchasePrice;

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
     * @return Portfolio
     */
    public function setId(string $id): Portfolio
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
     * @return Portfolio
     */
    public function setStock(Stock $stock): Portfolio
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): User|null
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return Portfolio
     */
    public function setUser(User $user): Portfolio
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount / self::PRICE_MULTIPLIER;
    }

    /**
     * @param int $amount
     *
     * @return Portfolio
     */
    public function setAmount(int $amount): Portfolio
    {
        $this->amount = round($amount * self::PRICE_MULTIPLIER);

        return $this;
    }

    /**
     * @return DateTime
     */
    public function getPurchaseDate(): DateTime
    {
        return $this->purchaseDate;
    }

    /**
     * @param DateTime $purchaseDate
     *
     * @return Portfolio
     */
    public function setPurchaseDate(DateTime $purchaseDate): Portfolio
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    /**
     * @return float
     */
    public function getPurchasePrice(): float
    {
        return $this->purchasePrice / self::PRICE_MULTIPLIER;
    }

    /**
     * @param int $purchasePrice
     *
     * @return Portfolio
     */
    public function setPurchasePrice(int $purchasePrice): Portfolio
    {
        $this->purchasePrice = round($purchasePrice * self::PRICE_MULTIPLIER);

        return $this;
    }
}