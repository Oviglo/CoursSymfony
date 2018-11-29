<?php 

namespace App\Menu;

use Knp\Menu\FactoryInterface;

class AdminMenuBuilder
{
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMenu()
    {
        $menu = $this->factory->createItem('root');

        // Création du menu
        $parent = $menu->addChild('menu.admin.articles', ['uri' => '#']); // uri => lien externe
        // Ajoute un sous-menu
        $parent->addChild('menu.admin.article_list', ['route' => 'admin_article_index']);
        $parent->addChild('menu.admin.article_new', ['route' => 'admin_article_new']);
        // $parent->addChild('menu.admin.article_new', ['route' => 'admin_article_edit', 'routeParameters' => ['id'=> 1]]);

        $menu->addChild('menu.admin.categories', ['route' => 'admin_category_index']);

        return $menu;
    }
}