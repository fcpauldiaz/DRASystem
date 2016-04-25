<?php

namespace CostoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
                    'label' => 'Escoja el método de filtro',
                    'required' => true,

                ])
            ->add('costo_monetario', 'choice', [
                'mapped' => false,
                 'choices' => [
                    'No' => 0,
                    'Si' => 1,
                ],
                'attr' => [
                    'class' => 'hide-element-decision',
                ],
                'required' => true,
                // always include this
                'choices_as_values' => true,
                ])
             ->add('fechaInicio', 'collot_datetime', ['pickerOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'weekStart' => 0,
                    'autoclose' => true,
                    'startView' => 'year',
                    'minView' => 'year',
                    'maxView' => 'decade',
                    'todayBtn' => false,
                    'todayHighlight' => true,
                    'keyboardNavigation' => true,
                    'language' => 'es',
                    'forceParse' => false,
                    'minuteStep' => 5,
                    'pickerReferer ' => 'default', //deprecated
                    'pickerPosition' => 'bottom-right',
                    'viewSelect' => 'month',
                    'showMeridian' => false,
                ],
                'attr' => [
                    'class' => 'hide-element fecha-inicial',
                ],
                'label_attr' => [
                    'class' => 'hide-element',
                ],
                'read_only' => true,
                'required' => false,
            ])
           ->add('fechaFinal', 'collot_datetime', ['pickerOptions' => [
                            'format' => 'dd/mm/yyyy',
                            'weekStart' => 0,
                            'autoclose' => true,
                            'startView' => 'year',
                            'minView' => 'year',
                            'maxView' => 'decade',
                            'todayBtn' => false,
                            'todayHighlight' => true,
                            'keyboardNavigation' => true,
                            'language' => 'es',
                            'forceParse' => false,
                            'minuteStep' => 5,
                            'pickerReferer ' => 'default', //deprecated
                            'pickerPosition' => 'bottom-right',
                            'viewSelect' => 'month',
                            'showMeridian' => false,
                        ],

                        'attr' => [
                            'class' => 'hide-element fecha-final',
                        ],

                        'label_attr' => [
                            'class' => 'hide-element',
                        ],

                        'read_only' => true,
                        'required' => false,

                    ])
            ->add('horas_extraordinarias', 'choice', [
                'choices' => [
                    'Sí' => 0,
                    'No' => 1,
                ],
                'label' => '¿Incluir horas extraordinarias?',
                'required' => true,
                // always include this
                'choices_as_values' => true,

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
