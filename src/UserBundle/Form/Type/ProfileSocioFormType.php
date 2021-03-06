<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileSocioFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // agregar campos personalizados
        $builder->add('nombre', null, [
                'label' => 'Nombre/s',
                'attr' => [
                    'placeholder' => 'Nombre/s',
                    'class' => 'form-control input-lg',
                ],

            ])
            ->add('apellidos', null, [
                'label' => 'Apellidos/s',
                'attr' => [
                        'placeholder' => 'Apellidos',
                        'class' => 'form-control input-lg',
                    ],
                ])
            ->add('username', null, [
                    'label' => 'Usuario',
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => [
                        'class' => 'form-control input-lg',
                        'placeholder' => 'Nombre de Usuario',
                    ],
                ])
            ->add('email', EmailType::class, [
                    'label' => 'Correo',
                    'translation_domain' => 'FOSUserBundle',
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control input-lg',
                        'placeholder' => 'Correo electrónico',
                    ],
                ])

            ->remove('plainPassword', 'repeated', [
                'label' => 'Contraseña',
                'type' => 'password',
                'options' => ['translation_domain' => 'FOSUserBundle'],
                'first_options' => [
                    'label' => 'Contraseña',
                    'attr' => [
                        'class' => 'form-control input-lg',
                        'placeholder' => 'Contraseña',
                    ],
                ],
                'second_options' => [
                    'label' => 'Repetir Contraseña',
                    'attr' => [
                        'class' => 'form-control input-lg',
                        'placeholder' => 'Repetir Contraseña',
                    ],
                ],
                'invalid_message' => 'fos_user.password.mismatch',

            ])

              ->add('submit', SubmitType::class, [
                        'label' => 'Guardar',
                        'attr' => [
                                'class' => 'btn btn-primary',
                            ],
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
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'user_registration_socio';
    }
}
