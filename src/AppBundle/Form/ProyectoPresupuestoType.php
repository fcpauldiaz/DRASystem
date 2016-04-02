<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProyectoPresupuestoType extends AbstractType
{
    private $usuario;

    //única forma que encontré para guardar el campo ingresado por
    //porque los formularios embedded no pasan por el controller
    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    /* @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombrePresupuesto')
            ->add('usuarios', 'bootstrap_collection', [
                    'type' => 'entity',
                    'label' => 'Agregar usuarios',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'add_button_text' => 'Agregar Usuario involucrado',
                    'delete_button_text' => 'Eliminar Usuario',
                    'sub_widget_col' => 9,
                    'button_col' => 3,
                    'attr' => [
                            'class' => 'select2',
                            'help_text' => 'Escoja los usuarios que desea vincular con el proyecto'
                        ],
                    'options' => [
                       'empty_value' => 'Seleccionar Usuario',
                        'class' => 'UserBundle:Usuario',
                        'required' => true,
                        'label' => 'Buscador de Usuarios',
                        'property' => 'codigoString',
                        'attr' => [
                            'class' => 'select2',
                            
                        ],
                    ],
                    'required' => true,
                ])
          ->add('presupuestoIndividual', 'bootstrap_collection', [
                    'type' => new RegistroHorasPresupuestoType($this->usuario),
                    'label' => 'Registro Horas Presupuesto',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'add_button_text' => 'Agregar Registro',
                    'delete_button_text' => 'Eliminar Registro',
                    'sub_widget_col' => 6,
                    'button_col' => 3,
                    'by_reference' => false, //esta linea también es importante para que se guarde la ref
                    'cascade_validation' => true,
                    'attr' => [
                            'class' => 'select2',
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
            'data_class' => 'AppBundle\Entity\ProyectoPresupuesto',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_proyectopresupuesto';
    }
}
