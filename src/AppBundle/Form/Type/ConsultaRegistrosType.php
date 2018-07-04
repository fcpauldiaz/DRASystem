<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Cliente;
use AppBundle\Entity\ProyectoPresupuesto;
use UserBundle\Entity\UsuarioTrabajador;
use SC\DatetimepickerBundle\Form\Type\DatetimeType;

class ConsultaRegistrosType extends AbstractType
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
            ->add('horas_extraordinarias', ChoiceType::class, [
                'choices' => [
                    'No' => 0,
                    'Sí' => 1,
                ],
                'label' => '¿Horas extraordinarias?',
                'required' => true,
                // always include this
                'choices_as_values' => true,
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
                'multiple' => false
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
        return 'consulta_usuario_horas_registro';
    }
}
