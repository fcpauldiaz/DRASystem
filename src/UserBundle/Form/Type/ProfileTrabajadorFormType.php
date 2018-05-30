<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ProfileTrabajadorFormType extends AbstractType
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
            ->add('direccion', null, [
                'label' => 'Dirección',
                'attr' => [
                    'class' => 'form-control input-lg',
                    'placeholder' => 'Dirección',
                ],
                'required' => true,
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
                    'placeholder' => 'Número de Identificación Tributaria (sin guión)',
                ],
                'required' => true,
                'constraints' => [
                    new Callback([$this, 'validarNIT']),
                ],

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
            ->add('initials', TextType::class, [
                'required' => true,
            ])
            ->add('puestos')
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
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\UsuarioTrabajador',
        ));
    }
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'user_registration';
    }

    /* Validar que el NIT no tenga guiones
    *
    * @param Array                     $data    contiene los datos del formulario
    * @param ExecutionContextInterface $context
    */
    public function validarNIT($nit, ExecutionContextInterface $context)
    {
        if (strpos($nit, '-') !== false) {
            $context->buildViolation('El NIT no puede tener guión')
                ->atPath('trabajador_registration')
                ->addViolation();
        }
    }
}
