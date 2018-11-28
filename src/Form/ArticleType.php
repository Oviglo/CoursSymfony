<?php 

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;

use App\Form\ImageType;

class ArticleType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'label' => 'article.title',
            ))

            ->add('categories', null, array(
                'label' => 'article.categories',
                'expanded' => true,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                } 
            ))

            ->add('image', ImageType::class, array(
                'label' => 'article.image',
            ))

            ->add('content', null, array(
                'label' => 'article.content',
            ))  
        ;

        // Evénement pour ajouter ou non le champ "deleteImage" 
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $entity = $event->getData(); // Entité envoyée au formulaire 
            $form = $event->getForm(); // Récupére le formulaire

            if (!is_null($entity->getImage())) { // S'il y a une image dans mon article
                // Ajout du champ "deleteImage" seulement s'il y a une image
                $form->add('deleteImage', Type\CheckboxType::class, array(
                    'label' => 'image.delete',
                    'required' => false, // Désactive le required
                ));
            }
        });
    }
}