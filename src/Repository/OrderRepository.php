<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }


    public function findAmountOfPayment()
    {
        return $this->createQueryBuilder('o')
            ->select("o.id", "SUM(p.amount) as amount")
            ->innerJoin("o.payments", "p")
            ->groupBy("p.client_order")
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findAmountOfInvoice($id)
    {
        return $this->createQueryBuilder('o')
            ->select("SUM(p.amount) as amount")
            ->innerJoin("o.payments", "p")
            ->groupBy("p.client_order")
            ->where("o.id = (:id)")
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult()
            ;
    }

    /**
     * @throws NonUniqueResultException|NoResultException
     */
    public function findLastInvoice($id)
    {
        return $this->createQueryBuilder('o')
            ->select("i.number")
            ->innerJoin("o.invoices", "i")
            ->where("o.id = (:id)")
            ->setParameter('id', $id)
            ->orderBy("o.id", "DESC")
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult();
    }



    public function findByState($state)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.state = :val')
            ->setParameter('val', $state)
            ->getQuery()
            ->getResult()
        ;
    }

}
