<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Article;
use App\Entity\ArticleFollow;

/**
 * @Route("/article", name="article_")
 */
class ArticleController extends Controller
{
    /**
     * @Route("/{page}", requirements={"page" = "\d+"}, defaults={"page" = 1}, name="index")
     */
    public function index($page)
    {
        $em = $this->getDoctrine()->getManager();
        // Nombre d'article à afficher par page
        $count = 10; 
        $user = $this->getUser(); // Utilisateur courant
        $isPublished = true;
        if (is_object($user) && $user->hasRole('ROLE_SUPER_ADMIN')) {
            $isPublished = null;
        }
        // Récupére les article en fonction de la page et du nombre 
        $entities = $em->getRepository(Article::class)->findByPage($page, $count, $isPublished);
        // Calcul le nombre de page
        $nbPages = ceil(count($entities) / $count);

        return $this->render('article/index.html.twig', [
            'entities' => $entities,
            'nbPages' => $nbPages,
            'page' => $page,
            'count' => count($entities),
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

    /**
     * @Route("/show/{id}", requirements={"id" = "\d+"}, name="show")
     */
    public function show(Article $entity /* $id */)
    {
        /*
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Article::class)->findOneById($id);
        */
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $af = $em->getRepository(ArticleFollow::class)->findOneBy(array(
            'article' => $entity,
            'user' => $user,
        )); 
        $isFollow = is_object($af);
        
        return $this->render('/article/show.html.twig', array(
            'entity' => $entity,
            'isFollow' => $isFollow,
        ));
    }

    /**
     * @Route("/follow/{id}", requirements={"id" = "\d+"}, name="follow")
     */
    public function follow(Article $entity)
    {
        // Tester si l'utilisateur suis ou non l'article
        $user = $this->getUser();

        if (is_object($user)) {
            $em = $this->getDoctrine()->getManager();
            $af = $em->getRepository(ArticleFollow::class)->findOneBy(array(
                'article' => $entity,
                'user' => $user,
            ));

            if ($af !== null) { // L'utilisateur suis déjà l'article

                $em->remove($af);

            } else { // L'utilisateur ne suis pas l'article

                $af = new ArticleFollow();
                $af->setArticle($entity)->setUser($user);

                $em->persist($af);
            }

            $em->flush();
        }

        return $this->redirectToRoute('article_show', array('id' => $entity->getId()));
    }
}
