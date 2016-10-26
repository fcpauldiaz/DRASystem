<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints;

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
            ->add('email', 'email', [
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
            ->add('codigo', 'entity', [
                'class' => 'UserBundle:Codigo',
                'label' => 'Código Interno DRA',
                'property' => 'codigoCompleto',
                'attr' => [
                    'class' => 'select2',
                ],
                'required' => true,
                'empty_value' => 'Seleccionar Código',
            ])

            ->add('submit', 'submit', [
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
        return 'fos_user_registration';
    }

    public function getName()
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
