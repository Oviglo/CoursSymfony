<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Article;

class ArticleController extends Controller
{
    /**
     * @Route("/article", name="article")
     */
    public function index()
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    public function recentArticles($count = 5)
    {
        $em = $this->getDoctrine()->getManager();
        // 'App\Entity\Article'
        $entities = $em->getRepository(Article::class)->findBy(['publish' => true], ['id' => 'DESC'], $count);

        return $this->render('article/_recent_articles.html.twig', array(
            'entities' => $entities,
        ));
    }
}
