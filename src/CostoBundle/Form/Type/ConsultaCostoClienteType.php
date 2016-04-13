<?php

namespace CostoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConsultaCostoClienteType extends AbstractType
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
                ],
                'read_only' => true,

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
                    'class' => 'fecha-final',
                ],
                'read_only' => true,

            ])
            ->add('cliente', 'entity', [
                'class' => 'AppBundle:Cliente',
                'empty_value' => 'Escoja el cliente',

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
        return 'consulta_costo';
    }
}
