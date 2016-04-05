<?php

namespace CostoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ConsultaPresupuestoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('proyecto', EntityType::class, [
                'placeholder' => 'Seleccionar Proyecto Presupuesto',
                'class' => 'AppBundle:ProyectoPresupuesto',
                'label' => 'Buscador Proyectos Presupuesto',
                'attr' => [
                    'class' => 'select2',
                ],
                'required' => true,
            ])
            ->add('consulta_filtro', ChoiceType::class,
                [
                    'choices' => [
                        'Actividad' => 'Actividad',
                        'Usuarios' => 'Usuarios',
                        'Cliente' => 'Cliente',
                    ],
                    'choices_as_values' => true,
                    'placeholder' => 'Seleccionar Tipo de filtro',
                    'preferred_choices' => ['Actividad' => 'Actividad'],
                    'label' => 'Escoja el método de filtro',
                    'required' => true,

                ])
            ->add('submit', SubmitType::class,
                [
                    'label' => 'Buscar',
                    'attr' => [
                        'class' => 'btn btn-primary btn-block',
                    ],

                ])

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'consultabundle_consultapresupuesto';
    }
}
