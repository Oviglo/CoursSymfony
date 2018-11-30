<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/profile/{username}", name="profile")
     */
    public function profile($username)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(User::class)->findOneByUsername($username);
        return $this->render('/user/profile.html.twig', array(
            'entity' => $entity,
        ));
    }
}
