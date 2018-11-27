<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Category;
use App\Form\CategoryType;

/**
 * @Route("/admin/category", name="admin_category_")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository(Category::class)->findAll();

        return $this->render('admin/category/index.html.twig', [
            'entities' => $entities,
        ]);
    }

    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request)
    {
        $entity = new Category;
        $form = $this->createForm(CategoryType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $t = $this->get('translator');
            $this->addFlash('success', $t->trans('category.new.success'));

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     */
    public function edit(Request $request, Category $entity)
    {
        $form = $this->createForm(CategoryType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $t = $this->get('translator');
            $this->addFlash('success', $t->trans('category.edit.success'));

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id" = "\d+"})
     */
    public function delete(Request $request, Category $entity)
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_category_delete', ['id' => $entity->getId()])) // action=""
            ->setMethod('DELETE')
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();

            // Crée un message de confirmation
            $t = $this->get('translator');
            $this->addFlash('success', $t->trans('category.delete.success'));

            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/delete.html.twig', array(
            'form' => $form->createView(), // Envoi le formulaire à la vue
            'entity' => $entity,
        ));
    }
}
