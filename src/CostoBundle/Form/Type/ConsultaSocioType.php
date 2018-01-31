<?php

namespace CostoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SC\DatetimepickerBundle\Form\Type\DatetimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use UserBundle\Entity\UsuarioSocio;

class ConsultaSocioType extends AbstractType
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
                    'class' => 'fecha-inicial',
                    'read_only' => true,
                ],
                

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
                    'class' => 'fecha-final',
                    'read_only' => true,
                ],
                

            ])
            ->add('socio', EntityType::class, [
                'required' => false,
                'label' => 'Socio/s asignados',
                'class' => UsuarioSocio::class,
                'attr' => [
                    'class' => 'select2',
                ],

            ])
          ->add('horas_extraordinarias', ChoiceType::class, [
                'choices' => [
                    'Sí' => 0,
                    'No' => 1,
                ],
                'label' => '¿Incluir horas extraordinarias?',
                'required' => true,
                // always include this
                'choices_as_values' => true,

            ])
           ->add('proyecto_o_usuarios', ChoiceType::class, [
                'choices' => [
                    'Proyecto Presupuesto' => 0,
                    'Usuarios' => 1,
                ],
                'label' => 'Escoja tipo de consulta',
                'attr' => [
                    'help_text' => 'La consulta puede ser por el proyecto de presupuesto o por cada usuario relacionado',
                    'class' => 'select2'
                ],
                'required' => true,
                // always include this
                'choices_as_values' => true,

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
        return 'consulta_socio';
    }
}
