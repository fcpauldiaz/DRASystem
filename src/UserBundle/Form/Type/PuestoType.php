<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PuestoType extends AbstractType
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
            ->add('tipoPuesto', 'entity', [
                'class' => 'UserBundle:TipoPuesto',
                'attr' => [
                    'class' => 'select2 form-control input-lg',
                ],

            ])
            ->add('departamento', 'entity', [
                'class' => 'UserBundle:Departamento',
                'label' => 'Departamento',
                'attr' => [
                    'class' => 'select2 form-control input-lg',
                ],
            ])
            ->add('usuario', 'entity', [
                'class' => 'UserBundle:UsuarioTrabajador',
                'data' => $this->usuario,
                'attr' => [
                    'class' => 'select2',
                ],
            ])
        ;
        $builder->addEventListener(
                FormEvents::POST_SUBMIT,
                [$this, 'onPostData']
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Puesto',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'userbundle_puesto';
    }

    /**
     * Forma de agregar el permiso en caso de control total
     *  se crea un permiso temporal para agregar el segundo ROLE
     * Esto se hace para poder mostrar usuarios con los queries
     * Ya que no se puede utilizar la jerarquía en sql
     * Esto puede ocasionar un BC si hay cambios.
     *
     * @param FormEvent $event Evento después de mandar la información del formulario
     */
    public function onPostData(FormEvent $event)
    {
        $puesto = $event->getData();
        $usuario = $puesto->getUsuario();
        $permisos = $puesto->getTipoPuesto()->getPermisos();
        foreach ($permisos as $permiso) {
            if ($permiso->getPermiso() == 'ROLE_ADMIN') {
                $usuario->addRole('ROLE_ASIGNACION');
            }
        }
    }
}
