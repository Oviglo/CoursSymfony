<?php 

namespace App\EventListener;

use Symfony\Component\EventDispatcher\GenericEvent;

class NotificationListener
{
    public function onArticleAdd(GenericEvent $event) :void
    {
        $entity = $event->getSubject(); // Objet Article

        $file = __DIR__.'/../../var/notification.log';
        $fileContent = file_get_contents($file);

        $fileContent .= "L'article {$entity->getTitle()} a été ajouté\n";
        file_put_contents($file, $fileContent);
    }
}