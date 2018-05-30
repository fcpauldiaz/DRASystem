<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use UserBundle\Entity\Usuario;

class AsignacionType extends AbstractType
{
    private $usuario;

    public function __construct()
    {
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->usuario = $options['user'];
        $builder
            ->add('usuarios', EntityType::class, [
                'class' => 'UserBundle:UsuarioTrabajador',
                'multiple' => true,
                'required' => true,
                'label' => 'Usuarios a asignar',
                'attr' => [
                    'class' => 'select2',
                ],
            ])
            ->add('usuarioAsignar', EntityType::class, [
                'class' => Usuario::class,
                'required' => true,
                'label' => 'Asignar usuarios a este usuario',
                'attr' => [
                    'class' => 'select2',
                ],
                'data' => $this->usuario,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Guardar',
            ])

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
            'user' => null,
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_asignacion_usuarios';
    }
}
