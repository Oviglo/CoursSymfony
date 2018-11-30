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

    public function findByPage($page = 1, $count = 10, $isPublished = null)
    {
        $offset = ($page - 1) * $count; // Calcul offset de la requête

        $queryBuilder = $this->createQueryBuilder('a')
            ->select('a, u, c')
            ->leftJoin('a.user', 'u')
            ->leftJoin('a.categories', 'c')
            ->setFirstResult($offset) // OFFSET
            ->setMaxResults($count) // LIMIT
            ->orderBy('a.id', 'DESC')
        ;

        // WHERE sur la publication
        if(!is_null($isPublished)) {
            $queryBuilder->where('a.publish = :published')
                ->setParameter(':published', $isPublished)
            ;

            // ->where('a.publish = ' . $isPublished);
        }

        return new Paginator($queryBuilder); // Liste + nombre total
    }
}