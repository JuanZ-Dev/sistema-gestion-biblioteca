<?php

namespace App\Repository;

use App\Entity\Multa;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Multa>
 */
class MultaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Multa::class);
    }

    public function idPrestamo(?int $multaId): array
    {
        return $this->createQueryBuilder('m')
            ->select('p.id')
            ->join('m.prestamo', 'p')
            ->where('m.id = :id')
            ->setParameter('id', $multaId)
            ->getQuery()
            ->getSingleColumnResult();
    }

    //    /**
    //     * @return Multa[] Returns an array of Multa objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Multa
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
