<?php

namespace App\Repository;

use App\Entity\ArticleFollow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ArticleFollow|null find($id, $lockMode = null, $lockVersion = null)
 * @method ArticleFollow|null findOneBy(array $criteria, array $orderBy = null)
 * @method ArticleFollow[]    findAll()
 * @method ArticleFollow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleFollowRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ArticleFollow::class);
    }

    // /**
    //  * @return ArticleFollow[] Returns an array of ArticleFollow objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ArticleFollow
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
