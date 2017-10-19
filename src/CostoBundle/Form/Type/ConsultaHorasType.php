<?php

namespace CostoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ConsultaHorasType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaInicio', 'collot_datetime', ['pickerOptions' => [
                   'format' => 'dd/mm/yyyy',
                    'weekStart' => 0,
                    'autoclose' => true,
                    'startView' => 'month',
                    'minView' => 'month',
                    'maxView' => 'decade',
                    'todayBtn' => false,
                    'todayHighlight' => true,
                    'keyboardNavigation' => true,
                    'language' => 'es',
                    'forceParse' => false,
                    'minuteStep' => 60,
                    'pickerReferer ' => 'default', //deprecated
                    'pickerPosition' => 'bottom-right',
                    'viewSelect' => 'month',
                    'showMeridian' => false,
                ],
                 'attr' => [
                    'class' => 'fecha-inicial',
                ],
                'read_only' => true,

            ])
            ->add('fechaFinal', 'collot_datetime', ['pickerOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'weekStart' => 0,
                    'autoclose' => true,
                    'startView' => 'month',
                    'minView' => 'month',
                    'maxView' => 'decade',
                    'todayBtn' => false,
                    'todayHighlight' => true,
                    'keyboardNavigation' => true,
                    'language' => 'es',
                    'forceParse' => false,
                    'minuteStep' => 60,
                    'pickerReferer ' => 'default', //deprecated
                    'pickerPosition' => 'bottom-right',
                    'viewSelect' => 'month',
                    'showMeridian' => false,
                ],
                 'attr' => [
                    'class' => 'fecha-final',
                ],
                'read_only' => true,

            ])
            ->add('consulta_filtro', ChoiceType::class,
                [
                    'choices' => [
                        'Usuario' => 'Usuario',
                        'Presupuesto' => 'Presupuesto',
                        'Cliente' => 'Cliente',
                    ],
                    'choices_as_values' => true,
                    'placeholder' => 'Seleccionar Tipo de filtro',
                    'label' => 'Escoja el método de filtro',
                    'required' => true,
                    'attr' => [
                    'class' => 'select2',
                    'mapped' => false
                ],
              ]
            )
        ->add('horas_extraordinarias', 'choice', [
                'choices' => [
                    'No' => 0,
                    'Sí' => 1,
                ],
                'label' => '¿Horas extraordinarias?',
                'required' => true,
                // always include this
                'choices_as_values' => true,
            ])
            ->add('usuario', 'entity', [
                'class' => 'UserBundle:UsuarioTrabajador',
                'property' => 'codigoString',
                'attr' => [
                    'help_text' => 'AS para asistente, EN para encargado, SU para supervisor, GE para gerente, SC para socio',
                ],
                'empty_value' => 'Seleccione el usuario',
                'required' => false,
                'label' => 'Horas realizadas por',
                'attr' => [
                    'class' => 'select2',
                ],
                'multiple' => true
            ])
            ->add('cliente', 'entity', [
                'class' => 'AppBundle:Cliente',
                'required' => true,
                'property' => 'showSearchParams',
                'empty_value' => 'Seleccione el cliente',
                'required' => false,
                 'attr' => [
                    'class' => 'select2',
                ],
                'multiple' => true
            ])
            ->add('proyectoPresupuesto', 'entity', [
                'class' => 'AppBundle:ProyectoPresupuesto',
                'required' => false,
                'empty_value' => 'Seleccione el presupuesto',
                'attr' => [
                    'class' => 'select2',
                ],
                'multiple' => true
            ])
            ->add('submit', 'submit', [
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
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'consulta_usuario';
    }
}
