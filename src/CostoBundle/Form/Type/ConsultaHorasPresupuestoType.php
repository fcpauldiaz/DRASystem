<?php

namespace CostoBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SC\DatetimepickerBundle\Form\Type\DatetimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\ProyectoPresupuesto;
use AppBundle\Entity\Cliente;
use UserBundle\Entity\UsuarioTrabajador;

class ConsultaHorasPresupuestoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaInicio', DatetimeType::class, ['pickerOptions' => [
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
                    'read_only' => true,
                ],
                

            ])
            ->add('fechaFinal', DatetimeType::class, ['pickerOptions' => [
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
                    'read_only' => true,
                ],
                

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
            ->add('usuario', EntityType::class, [
                'class' => UsuarioTrabajador::class,
                'choice_lable' => 'codigoString',
                'attr' => [
                    'help_text' => 'AS para asistente, EN para encargado, SU para supervisor, GE para gerente, SC para socio',
                ],
                'placeholder' => 'Seleccione el usuario',
                'required' => false,
                'label' => 'Horas realizadas por',
                'attr' => [
                    'class' => 'select2',
                ],
                'multiple' => true
            ])
            ->add('cliente', EntityType::class, [
                'class' => Cliente::class,
                'required' => true,
                'choice_label' => 'showSearchParams',
                'placeholder' => 'Seleccione el cliente',
                'required' => false,
                 'attr' => [
                    'class' => 'select2',
                ],
                'multiple' => true
            ])
            ->add('proyectoPresupuesto', EntityType::class, [
                'class' => ProyectoPresupuesto::class,
                'required' => false,
                'placholder' => 'Seleccione el presupuesto',
                'attr' => [
                    'class' => 'select2',
                ],
                'multiple' => true
            ])
            ->add('submit', SubmitType::class, [
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
        return 'consulta_usuario';
    }
}
