<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AsignacionType extends AbstractType
{

    private $usuario;

    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usuarios', 'entity', [
                'class' => 'UserBundle:UsuarioTrabajador',
                'multiple' => true,
                'required' => true,
                'label' => 'Usuarios a asignar',
                'attr' => [
                    'class' => 'select2'
                ]
            ])
            ->add('usuarioAsignar', 'entity', [
                'class' => 'UserBundle:Usuario',
                'required' => true,
                'label' => 'Asignar usuarios a este usuario',
                'attr' => [
                    'class' => 'select2'
                ],
                'data' => $this->usuario,
            ])
            ->add('submit', 'submit', [
                'label' => 'Guardar',
            ])

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_asignacion_usuarios';
    }
}
