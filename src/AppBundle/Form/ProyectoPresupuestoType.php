<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use AppBundle\Form\RegistroHorasPresupuestoType;

class ProyectoPresupuestoType extends AbstractType
{
    /* @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder
          ->add('nombrePresupuesto')
          ->add('presupuestoIndividual', 'bootstrap_collection', [
                    'type' => new RegistroHorasPresupuestoType(),
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
            'data_class' => 'AppBundle\Entity\ProyectoPresupuesto'
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
