<?php 

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

use App\Entity\Article;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findByPage($page = 1, $count = 10)
    {
        $offset = ($page - 1) * $count; // Calcul offset de la requête

        $queryBuilder = $this->createQueryBuilder('a')
            ->select('a')
            ->setFirstResult($offset) // OFFSET
            ->setMaxResults($count) // LIMIT
        ;

        return new Paginator($queryBuilder); // Liste + nombre total
    }
}