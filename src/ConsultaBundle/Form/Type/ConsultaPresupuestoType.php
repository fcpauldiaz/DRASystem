<?php

namespace ConsultaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConsultaPresupuestoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('proyecto', 'entity', [
                'empty_value' => 'Seleccionar Proyecto Presupuesto',
                'class' => 'AppBundle:ProyectoPresupuesto',
                'label' => 'Buscador Proyectos Presupuesto',
                'attr' => [
                    'class' => 'select2',
                ],
                'required' => false,
            ])
            ->add('submit','submit', 
                [
                    'label' => 'Buscar',
                    'attr' =>[
                        'class' => 'btn btn-primary btn-block'
                    ],


                ])

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'consultabundle_consultapresupuesto';
    }
}
