<?php

namespace App\Repository;

use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invoice>
 *
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    //    /**
    //     * @return Invoice[] Returns an array of Invoice objects
    //     */

    public function findOverdueInvoices()
    {
        return $this->createQueryBuilder('i')
            ->join('i.paymentStatus','ps')
            ->where('i.due_date < :now')
            ->andWhere('ps.name != :paid')
            ->setParameter('now', new \DateTime())
            ->setParameter('paid','Paid')
            ->getQuery()
            ->getResult();
    }
    public function listInvoicesByPaymentStatus(string $value)
    {
        return $this->createQueryBuilder('i')
        ->join('i.paymentStatus','ps')
        ->where('ps.name = :val')
        ->setParameter('val',$value)
        ->join('i.customer','c')
        ->groupBy('c.id')
        ->orderBy('i.id','ASC')
        ->getQuery()
        ->getResult();
    }
    
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Invoice
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
