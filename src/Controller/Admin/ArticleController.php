<?php 

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route; // Définir les routes en annotations

use App\Form\ArticleType;
use App\Entity\Article;

/**
 * @Route("/admin/article", name="admin_article_")
 */
class ArticleController extends Controller 
{
    /**
     * @Route("/{page}", name="index", requirements={"page" = "\d+"}, defaults={"page" = 1})
     */
    public function index($page)
    {
        $count = 20; // Nombre d'articles par page
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository(Article::class)->findByPage($page, $count); // Récupére tous les articles en db

        $nbPages = ceil(count($entities) / $count);

        // Retourne un objet Response avec le contenu du fichier template
        return $this->render('admin/article/index.html.twig', array(
            'entities' => $entities,
            'page' => $page, // Page courante pour activer le bouton
            'nbPages' => $nbPages,

        ));
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request)
    {
        $entity = new Article;
        $form = $this->createForm(ArticleType::class, $entity);
        $form->handleRequest($request); // Envoi les données de requêtes (POST) au formulaire

        if ($form->isSubmitted() && $form->isValid()) { // Si le form est envoyé et valide
            // Ajout du nouvel article dans la db
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity); // Prépare la requête
            $em->flush(); // Execute la requête

            // Crée un message de confirmation
            $t = $this->get('translator');
            $this->addFlash('success', $t->trans('article.new.success'));

            return $this->redirectToRoute('admin_article_index'); // Retour sur la liste des articles
        }

        return $this->render('admin/article/new.html.twig', array(
            'form' => $form->createView(), // Envoi le formulaire à la vue
        ));
    }

    /**
     * @Route("/edit/{id}", name="edit", requirements={"id" = "\d+"})
     */
    public function edit(Request $request, Article $entity)
    {
        $form = $this->createForm(ArticleType::class, $entity);
        $form->handleRequest($request); // Envoi les données de requêtes (POST) au formulaire

        if ($form->isSubmitted() && $form->isValid()) { // Si le form est envoyé et valide
            // Ajout du nouvel article dans la db
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity); // Prépare la requête
            $em->flush(); // Execute la requête

            // Crée un message de confirmation
            $t = $this->get('translator');
            $this->addFlash('success', $t->trans('article.edit.success'));

            return $this->redirectToRoute('admin_article_index'); // Retour sur la liste des articles
        }

        return $this->render('admin/article/edit.html.twig', array(
            'form' => $form->createView(), // Envoi le formulaire à la vue
        ));
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id" = "\d+"})
     */
    public function delete(Request $request, Article $entity)
    {
        $form = $this->createFormBuilder()
            ->setAction( $this->generateUrl('admin_article_delete', ['id' => $entity->getId()]) ) // action=""
            ->setMethod('DELETE')
            ->getForm()
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();

            // Crée un message de confirmation
            $t = $this->get('translator');
            $this->addFlash('success', $t->trans('article.delete.success'));

            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('admin/article/delete.html.twig', array(
            'form' => $form->createView(), // Envoi le formulaire à la vue
            'entity' => $entity,
        ));
    }
}