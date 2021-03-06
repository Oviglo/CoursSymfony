<?php
namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Requête optimisée avec des jointures
     */
    public function findOneByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->select('u, a, c, i')
            ->leftJoin('u.articles', 'a')
            ->leftJoin('a.categories', 'c')
            ->leftJoin('a.image', 'i')
            ->where('u.username = :username')
            ->setParameter(':username', $username)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}