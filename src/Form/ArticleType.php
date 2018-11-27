<?php 

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Doctrine\ORM\EntityRepository;

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

            ->add('image', null, array(
                'label' => 'article.image',
            ))

            ->add('content', null, array(
                'label' => 'article.content',
            )) 
        ;
    }
}