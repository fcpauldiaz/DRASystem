<?php

namespace CostoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use SC\DatetimepickerBundle\Form\Type\DatetimeType;
use UserBundle\Entity\UsuarioTrabajador;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ConsultaGerenteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaInicio', DateTime::class, ['pickerOptions' => [
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
            ->add('fechaFinal', DateTime::class, ['pickerOptions' => [
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
              ->add('gerente', EntityType::class, [
                'required' => false,
                'label' => 'Gerentes',
                'attr' => [
                  'class' => 'select2'
                ],
                'class' => UsuarioTrabajador::class,
                'query_builder' => function (\Doctrine\ORM\EntityRepository $er) {
                    return $er->createQueryBuilder('usuario')
                        ->leftJoin('usuario.puestos', 'puesto')
                        ->leftJoin('puesto.tipoPuesto', 'tipopuesto')
                        ->where('tipopuesto.nombrePuesto LIKE :nombre')
                        ->setParameter('nombre', '%Gerente%');
                },

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
            ->add('submit', SubmitType::class, [
                'label' => 'Buscar',
                'attr' => [
                    'class' => 'btn btn-primary btn-block',
                ],

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
