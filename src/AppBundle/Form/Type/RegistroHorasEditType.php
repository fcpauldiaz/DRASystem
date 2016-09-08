<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistroHorasEditType extends AbstractType
{
    private $usuario;

    public function __construct($usuario = null)
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
                'attr' => [
                    'class' => 'select2',
                ],

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
                'attr' => [
                    'class' => 'select2',
                ],

            ])
             ->add('actividad', 'entity', [
                'class' => 'AppBundle:Actividad',
                'required' => true,
                'empty_value' => 'Seleccione la actividad',
                'attr' => [
                    'class' => 'select2',
                ],
            ])
            ->add('horasInvertidas', null, [
                'label' => 'Horas invertidas',
                'required' => true,
            ])
            ->add('ingresadoPor', 'entity', [
                'class' => 'UserBundle:UsuarioTrabajador',
                'property' => 'codigoString',
                'data' => $this->usuario,
                'attr' => [
                    'help_text' => 'AS para asistente, EN para encargado, SU para supervisor, GE para gerente, SC para socio',
                    'class' => 'select2',
                ],

            ])
             ->add('horasExtraordinarias', null, [
                'label' => 'Horas Extraordinarias',
                'attr' => [
                    'help_text' => 'Marque esta opción si las horas que está ingresando son extraordinarias',
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
