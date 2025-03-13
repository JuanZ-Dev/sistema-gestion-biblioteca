<?php

namespace App\Repository;

use App\Entity\Prestamo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Prestamo>
 */
class PrestamoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Prestamo::class);
    }

    public function idEjemplar(?int $prestamoId): array
    {
        return $this->createQueryBuilder('p')
            ->select('e.id')
            ->join('p.ejemplar', 'e')
            ->where('p.id = :id')
            ->setParameter('id', $prestamoId)
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function idUsuario(?int $prestamoId): array
    {
        return $this->createQueryBuilder('p')
            ->select('u.id')
            ->join('p.usuario', 'u')
            ->where('p.id = :id')
            ->setParameter('id', $prestamoId)
            ->getQuery()
            ->getSingleColumnResult();
    }

    public function prestamosRetrasados(?array $prestamoId): QueryBuilder
    {
        if (!empty($prestamoId)) {
            return $this->createQueryBuilder('p')
                ->where('p.id = :prestamoId')
                ->setParameter('prestamoId', $prestamoId);
        }

        return $this->createQueryBuilder('p')
//            ->select('p.id')
            ->where('p.estado = :estado')
            ->setParameter('estado', 'retrasado');
    }

    //    /**
    //     * @return Prestamo[] Returns an array of Prestamo objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Prestamo
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
