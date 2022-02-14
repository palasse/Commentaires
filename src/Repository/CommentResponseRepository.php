<?php

namespace App\Repository;

use App\Entity\CommentResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommentResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentResponse[]    findAll()
 * @method CommentResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentResponse::class);
    }

    // /**
    //  * @return CommentResponse[] Returns an array of CommentResponse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommentResponse
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
