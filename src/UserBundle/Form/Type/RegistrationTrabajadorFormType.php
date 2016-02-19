<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationTrabajadorFormType extends AbstractType
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
            ->add('email', 'email', [
                    'label' => 'Correo',
                    'translation_domain' => 'FOSUserBundle',
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control input-lg',
                        'placeholder' => 'Correo electrónico',
                    ],
                ])

            ->add('plainPassword', 'repeated', [
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
             ->add('fechaIngreso', 'collot_datetime', ['pickerOptions' => [
                'format' => 'dd/mm/yyyy',
                'weekStart' => 0,
                'autoclose' => true,
                'startView' => 'month',
                'minView' => 'month',
                'maxView' => 'decade',
                'todayBtn' => true,
                'todayHighlight' => true,
                'keyboardNavigation' => true,
                'language' => 'es',
                'forceParse' => true,
                'minuteStep' => 5,
                'pickerReferer ' => 'default', //deprecated
                'pickerPosition' => 'bottom-right',
                'viewSelect' => 'month',
                'showMeridian' => false,

                ],
                'attr' => [
                    'placeholder' => 'Fecha de Ingreso a la empresa',
                    'class' => 'form-control input-lg',
                ],
                'required' => true,
            ])
               ->add('fechaEgreso', 'collot_datetime', ['pickerOptions' => [
                'format' => 'dd/mm/yyyy',
                'weekStart' => 0,
                'autoclose' => true,
                'startView' => 'month',
                'minView' => 'month',
                'maxView' => 'decade',
                'todayBtn' => true,
                'todayHighlight' => true,
                'keyboardNavigation' => true,
                'language' => 'es',
                'forceParse' => true,
                'minuteStep' => 5,
                'pickerReferer ' => 'default', //deprecated
                'pickerPosition' => 'bottom-right',
                'viewSelect' => 'month',
                'showMeridian' => false,

                ],
                'attr' => [
                    'placeholder' => 'Fecha de Egreso de la empresa(dejar en blanco)',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
            ])
               ->add('dpi', null, [
                    'label' => 'DPI',
                    'attr' => [
                        'class' => 'form-control input-lg',
                         'placeholder' => 'Documento Personal de Identificación',
                    ],
                    'required' => true,

                ])
               ->add('nit', null, [
                    'label' => 'NIT',
                    'attr' => [
                        'class' => 'form-control input-lg',
                        'placeholder' => 'Número de Identificación Tributaria',
                    ],
                    'required' => true,

                ])
                ->add('telefono', null, [
                    'label' => 'Teléfono',
                    'translation_domain' => 'FOSUserBundle',
                    'attr' => [
                        'class' => 'form-control input-lg',
                        'placeholder' => 'Teléfono',
                    ],
                    'required' => false,
                ])
                ->add('submit', 'submit', [
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
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'user_registration';
    }
}
