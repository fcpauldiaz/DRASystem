<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use UserBundle\Entity\Codigo;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationSocioFormType extends AbstractType
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
                    'constraints' => [
                        new Callback([$this, 'validarNombreUsuario']),
                    ],
                ])
            ->add('email', EmailType::class, [
                    'label' => 'Correo',
                    'translation_domain' => 'FOSUserBundle',
                    'required' => true,
                    'attr' => [
                        'class' => 'form-control input-lg',
                        'placeholder' => 'Correo electrónico @diazreyes.com',
                    ],
                    'constraints' => [
                        new Callback([$this, 'validarCorreoSocio']),
                    ],
                ])

            ->add('plainPassword', RepeatedType::class, [
                'label' => 'Contraseña',
                'type' => PasswordType::class,
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
           ->add('codigo', EntityType::class, [
                'class' => Codigo::class,
                'label' => false,
                'choice_label' => 'codigoCompleto',
                'required' => true,
                'placeholder' => 'Seleccionar Código',
                'attr' => [
                    'class' => 'select2',
                ],

            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Guardar',
                'attr' => [
                        'class' => 'btn btn-primary btn-block',
                    ],
            ])

            ;

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            [$this, 'onPostData']
        );
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
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'user_registration_socio';
    }

    /**
     * Forma de validar el correo de un catedrático.
     *
     * @param FormEvent $event Evento después de mandar la información del formulario
     */
    public function onPostData(FormEvent $event)
    {
        $usuario = $event->getData();
        $usuario->addRole('ROLE_ADMIN');
        $usuario->addRole('ROLE_ASIGNACION');
        //split name and substring to first letter.
        //explode params(delimiter, string, limit)
        //returns array of strings
        $first = substr(explode(' ', $usuario->getNombre(), 2)[0], 0, 1);
        $last = substr(explode(' ', $usuario->getApellidos(), 2)[0], 0, 1);
        $usuario->setInitials($first.$last);
    }

    /**
     * Validar que el correo pertenezca a un socio de la empresa.
     *
     * @param array                     $data    contiene los datos del formulario
     * @param ExecutionContextInterface $context
     */
    public function validarCorreoSocio($correo, ExecutionContextInterface $context)
    {
        if (strpos($correo, 'marco') === false &&
           strpos($correo, 'melani') === false &&
           strpos($correo, 'oscar') === false &&
           strpos($correo, 'julio') === false) {
            $context->buildViolation('El usuario no parece ser de un socio de la firma DRA')
                ->atPath('socio_registration')
                ->addViolation();
        }
    }

    /**
     * Validar que el nombre de usuario no tenga espacios en blanco.
     *
     * @param array                     $data    contiene los datos del formulario
     * @param ExecutionContextInterface $context
     */
    public function validarNombreUsuario($username, ExecutionContextInterface $context)
    {
        if (preg_match('/\s/', $username)) {
            $context->buildViolation('El nombre de usuario no puede tener espacios en blanco')
                ->atPath('socio_registration')
                ->addViolation();
        }
    }
}
