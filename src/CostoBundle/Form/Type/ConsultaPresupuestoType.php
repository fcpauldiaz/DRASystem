<?php

namespace CostoBundle\Form\Type;

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
            ->add('consulta_filtro','choice',
                [
                    'choices' => [
                        0 => 'Actividad',
                         1 => 'Usuarios',
                        2 => 'Cliente',
                    ],
                     'preferred_choices' => [0],
                    'label' => 'Escoja el método de filtro',

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
