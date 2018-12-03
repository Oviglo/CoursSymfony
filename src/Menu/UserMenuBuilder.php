<?php 

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UserMenuBuilder
{
    private $factory;
    private $tokenStorage;

    public function __construct(FactoryInterface $factory, TokenStorage $tokenStorage)
    {
        $this->factory = $factory;
        $this->tokenStorage = $tokenStorage;
    }

    public function createMenu()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $menu = $this->factory->createItem('root');

        $parent = $menu->addChild($user->getUsername(), ['uri' => '#']);
        $parent->setExtra('translation_domain', false); // Na pas traduire le pseudo

        $parent->addChild('logout', ['route' => 'fos_user_security_logout']);

        if ($user->hasRole('ROLE_SUPER_ADMIN')) {
            // Ajout menu SUPER ADMIN
        }

        return $menu;
    }
}