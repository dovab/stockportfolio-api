<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Infrastructure\Doctrine\Trait\SoftDeletableTrait;
use App\Infrastructure\Doctrine\Trait\TimestampableTrait;
use App\Repository\StockRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
#[ApiResource()]
class Stock
{
    use TimestampableTrait, SoftDeletableTrait;

    #[ORM\Id()]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[ORM\GeneratedValue(strategy: 'UUID')]
    private string $id;

    #[ORM\OneToMany(mappedBy: 'stock', targetEntity: StockPrice::class)]
    private Collection $prices;

    #[ORM\Column(type: Types::STRING, length: 10, nullable: false)]
    private string $ticker;

    #[ORM\Column(type: Types::STRING, length: 100, nullable: true)]
    private string|null $companyName = null;

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
     * @return Stock
     */
    public function setId(string $id): Stock
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    /**
     * @param Collection $prices
     *
     * @return Stock
     */
    public function setPrices(Collection $prices): Stock
    {
        $this->prices = $prices;

        return $this;
    }

    /**
     * @return string
     */
    public function getTicker(): string
    {
        return $this->ticker;
    }

    /**
     * @param string $ticker
     *
     * @return Stock
     */
    public function setTicker(string $ticker): Stock
    {
        $this->ticker = $ticker;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    /**
     * @param string|null $companyName
     *
     * @return Stock
     */
    public function setCompanyName(?string $companyName): Stock
    {
        $this->companyName = $companyName;

        return $this;
    }
}