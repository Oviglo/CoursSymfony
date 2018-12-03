<?php 

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AdminMenuBuilder
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

        // Création du menu
        $parent = $menu->addChild('menu.admin.articles', ['uri' => '#']); // uri => lien externe
        // Ajoute un sous-menu
        $parent->addChild('menu.admin.article_list', ['route' => 'admin_article_index']);
        $parent->addChild('menu.admin.article_new', ['route' => 'admin_article_new']);
        // $parent->addChild('menu.admin.article_new', ['route' => 'admin_article_edit', 'routeParameters' => ['id'=> 1]]);

        $menu->addChild('menu.admin.categories', ['route' => 'admin_category_index']);

        if ($user->hasRole('ROLE_FOURNISSEUR')) {
            $menu->addChild('fournisseur', ['uri' => '#']);
        }

        return $menu;
    }
}