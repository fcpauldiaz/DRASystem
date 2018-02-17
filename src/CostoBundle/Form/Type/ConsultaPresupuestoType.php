<?php

namespace CostoBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SC\DatetimepickerBundle\Form\Type\DatetimeType;
use AppBundle\Entity\ProyectoPresupuesto;

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
                'class' => ProyectoPresupuesto::class,
                'label' => 'Buscador Proyectos Presupuesto',
                'attr' => [
                    'class' => 'select2',
                ],
                'required' => true,
            ])
            ->add(
                'consulta_filtro',
                ChoiceType::class,
                [
                    'choices' => [
                        'Área' => 'Área',
                        'Usuarios' => 'Usuarios',
                        'Cliente' => 'Cliente',
                    ],
                    'choices_as_values' => true,
                    'placeholder' => 'Seleccionar Tipo de filtro',
                    'label' => 'Escoja el método de filtro',
                    'required' => true,
                    'attr' => [
                    'class' => 'select2 hide-element-decision',
                ],

            ]
            )
            ->add('horas_extraordinarias', ChoiceType::class, [
                'choices' => [
                    'No' => 1,
                    'Sí' => 0,
                ],
                'label' => '¿Incluir horas extraordinarias?',
                'required' => true,
                // always include this
                'choices_as_values' => true,
            ])
            ->add('fechaInicio', DatetimeType::class, ['pickerOptions' => [
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
                    'readonly' => true,
                    'class' => 'hide-element fecha-inicial'
                ],
                'label_attr' => [
                    'class' => 'hide-element',
                ]

            ])
            ->add('fechaFinal', DatetimeType::class, ['pickerOptions' => [
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
                    'readonly' => true,
                    'class' => 'hide-element fecha-final'
                ],
                'label_attr' => [
                    'class' => 'hide-element',
                ]

            ])
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Buscar',
                    'attr' => [
                        'class' => 'btn btn-primary btn-block',
                    ],

                ]
            )

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
