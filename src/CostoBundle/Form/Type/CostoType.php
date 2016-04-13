<?php

namespace CostoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CostoType extends AbstractType
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
            ->add('usuario', null, [
                'required' => true,
                ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CostoBundle\Entity\Costo',
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'consultabundle_costo';
    }
}
