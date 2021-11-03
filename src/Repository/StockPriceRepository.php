<?php

namespace App\Repository;

use App\Entity\StockPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StockPrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockPrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockPrice[]    findAll()
 * @method StockPrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockPriceRepository extends ServiceEntityRepository
{
    /**
     * StockPriceRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockPrice::class);
    }
}