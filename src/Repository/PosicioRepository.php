<?php

namespace App\Repository;

use App\Entity\Posicio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Posicio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Posicio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Posicio[]    findAll()
 * @method Posicio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PosicioRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Posicio::class);
    }

    /**
     * @return Posicio[] Returns an array of Posicio objects
     */
    public function findLikeNom($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.nom LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Posicio[] Returns an array of Posicio objects
    //  */
    /*
    public function findByExampleField($value)
    {
    return $this->createQueryBuilder('p')
    ->andWhere('p.exampleField = :val')
    ->setParameter('val', $value)
    ->orderBy('p.id', 'ASC')
    ->setMaxResults(10)
    ->getQuery()
    ->getResult()
    ;
    }
     */

    /*
public function findOneBySomeField($value): ?Posicio
{
return $this->createQueryBuilder('p')
->andWhere('p.exampleField = :val')
->setParameter('val', $value)
->getQuery()
->getOneOrNullResult()
;
}
 */
}
