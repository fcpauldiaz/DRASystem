<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationSocioFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // agregar campos personalizados
        $builder->add('nombre', 'text', ['label' => false,
            'attr' => [
                'placeholder' => 'Nombre/s',
                'class' => 'form-control input-lg'
            ],

            ])
            ->add('apellidos', null, ['label' => false,
                'attr' => [
                 'placeholder' => 'Apellidos',
                ],
                ])
            ->add('username', null, ['label' => false, 'translation_domain' => 'FOSUserBundle'])
            ->add('email', 'email', ['label' => false, 'translation_domain' => 'FOSUserBundle',
                'required' => false,
                ])
             ->add('telefono', 'integer', ['label' => false, 'translation_domain' => 'FOSUserBundle',
                'required' => false,
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

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'validation' => ['registration'],
        ]);
    }
    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'user_registration';
    }
}
