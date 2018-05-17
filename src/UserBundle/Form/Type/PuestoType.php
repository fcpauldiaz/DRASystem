<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use UserBundle\Entity\TipoPuesto;
use UserBundle\Entity\Departamento;
use UserBundle\Entity\UsuarioTrabajador;

class PuestoType extends AbstractType
{
    private $usuario;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->usuario = $options['user'];
        $builder
            ->add('tipoPuesto', EntityType::class, [
                'class' => TipoPuesto::class,
                'attr' => [
                    'class' => 'select2 form-control input-lg',
                ],

            ])
            ->add('departamento', EntityType::class, [
                'class' =>  Departamento::class,
                'label' => 'Departamento',
                'attr' => [
                    'class' => 'select2 form-control input-lg',
                ],
            ])
            ->add('usuario', EntityType::class, [
                'class' =>  UsuarioTrabajador::class,
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Puesto',
            'user' => null,
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
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
