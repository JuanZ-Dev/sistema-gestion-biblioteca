<?php

namespace App\Repository;

use App\Entity\Libro;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Libro>
 */
class LibroRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Libro::class);
    }

    public function estadosLibro(): array
    {
        return $this->createQueryBuilder('l')
            ->leftJoin('l.ejemplares', 'e')
            ->addSelect('l.id, l.titulo, l.isbn, l.anio_publicacion, l.idioma')
            ->addSelect('
                CASE 
                    WHEN COUNT(e.id) = 0 THEN :pendiente
                    WHEN SUM(CASE WHEN e.estado = :disponible THEN 1 ELSE 0 END) > 0 THEN :disponible 
                    ELSE :prestado
                END AS estado')
            ->addSelect('SUM(CASE WHEN e.estado = :disponible THEN 1 ELSE 0 END) AS disponibles')
            ->setParameter('disponible', 'Disponible')
            ->setParameter('prestado', 'Prestado')
            ->setParameter('pendiente', 'Pendiente')
            ->groupBy('l.id')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Libro[] Returns an array of Libro objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('l.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Libro
    //    {
    //        return $this->createQueryBuilder('l')
    //            ->andWhere('l.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
