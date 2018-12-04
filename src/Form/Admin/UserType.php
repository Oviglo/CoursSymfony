<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = array(
            'role.admin' => 'ROLE_ADMIN',
            'role.fournisseur' => 'ROLE_FOURNISSEUR',
            'role.user' => 'ROLE_USER',
        );

        $user = $this->tokenStorage->getToken()->getUser(); // Utilisateur connecté
        if (is_object($user) && $user->hasRole('ROLE_SUPER_ADMIN')) {
            $roles['role.super_admin'] = 'ROLE_SUPER_ADMIN';
        }

        $builder
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('plainPassword')
            ->add('roles', Type\ChoiceType::class, array(
                'multiple' => true,
                'choices' => $roles
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
