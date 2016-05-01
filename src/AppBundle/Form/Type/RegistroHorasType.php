<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistroHorasType extends AbstractType
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
             ->add('proyectoPresupuesto', 'entity', [
                'class' => 'AppBundle:ProyectoPresupuesto',
                'required' => true,
                'empty_value' => 'Seleccione el presupuesto asignado',

            ])
             ->add('fechaHoras', 'date', [
                'label' => 'Fecha de las horas invertidas',
                'input' => 'datetime',
                'widget' => 'choice',
                'model_timezone' => 'America/Guatemala',
                'view_timezone' => 'America/Guatemala',
                'format' => 'dd-MMM-yyyy',
                'data' => new \DateTime(),
                'attr' => [

                ],
                 'required' => true,

            ])
            ->add('cliente', 'entity', [
                'class' => 'AppBundle:Cliente',
                'required' => true,
                'empty_value' => 'Seleccione el cliente',

            ])
            ->add('actividad', 'entity', [
                'class' => 'AppBundle:Actividad',
                'required' => true,
                'empty_value' => 'Seleccione la actividad',
            ])
            ->add('horasInvertidas', null, [
                'label' => 'Horas invertidas',
                'required' => true,
            ])
            ->add('ingresadoPor', 'entity', [
                'class' => 'UserBundle:Usuario',
                'property' => 'codigoString',
                'data' => $this->usuario,
                'attr' => [
                    'help_text' => 'Usuario que realizó las horas',
                ],

            ])
             ->add('horasExtraordinarias', null, [
                'label' => 'Horas Extraordinarias',
                'attr' => [
                    'help_text' => 'Marque esta opción si las horas que está ingresando son extraordinarias',
                ],
            ])

            ->add('submit', 'submit', [
                    'label' => 'Guardar',
                    'attr' => [
                        'class' => 'btn btn-primary btn-block',
                    ],
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RegistroHoras',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_registrohoras';
    }
}
