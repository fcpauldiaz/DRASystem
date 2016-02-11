<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (class_exists('Symfony\Component\Security\Core\Validator\Constraints\UserPassword')) {
            $constraint = new UserPassword();
        } else {
            // Symfony 2.1 support with the old constraint class
            $constraint = new OldUserPassword();
        }

        // agregar campos personalizados
        $builder->add('nombre', null, ['label' => false])
                ->add('username', null, ['label' => false])
                ->add('email', 'email', ['label' => false])
                ->add('current_password', 'password', [
                'label' => false,
                'translation_domain' => 'FOSUserBundle',
                'mapped' => false,
                'constraints' => $constraint,
                ])
                ->add('plainPassword', 'repeated', [
                'label' => false,
                'type' => 'password',
                'options' => ['translation_domain' => 'FOSUserBundle'],
                'first_options' => ['label' => false],
                'second_options' => ['label' => false],
                'invalid_message' => 'fos_user.password.mismatch',
                ])
                ;
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function getName()
    {
        return 'user_profile';
    }
}
