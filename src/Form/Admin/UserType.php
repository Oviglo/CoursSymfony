<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('plainPassword')
            ->add('roles', Type\ChoiceType::class, array(
                'multiple' => true,
                'choices' => array(
                    'role.admin' => 'ROLE_ADMIN',
                    'role.fournisseur' => 'ROLE_FOURNISSEUR',
                    'role.user' => 'ROLE_USER',
                )
            ))
            ->add('type')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
