<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use UserBundle\Entity\Permiso;

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
             ->add('date', 'date', [
                'label' => 'Fecha',
                'input' => 'datetime',
                'widget' => 'choice',
                'model_timezone' => 'America/Guatemala',
                'view_timezone' => 'America/Guatemala',
                'format' => 'dd-MMM-yyyy',
                'data' => new \DateTime(),
                'attr' => [
                    'class' => 'select2',
                    'help_text' => 'Fecha de obtención del puesto',

                ],
                'years' => [
                    1980, 1981, 1982, 1983, 1984, 1985, 1986, 1987, 1988, 1989, 1990,
                    1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999, 2000, 2001,
                    2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012,
                    2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023,
                    2024, 2025, 2026, 2027, 2028, 2029, 2030,
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
