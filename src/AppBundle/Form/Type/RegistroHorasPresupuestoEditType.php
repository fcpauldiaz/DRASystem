<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistroHorasPresupuestoEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('cliente', 'entity', [
                'class' => 'AppBundle:Cliente',
                'required' => true,
                'attr' => [
                    'class' => 'select2',
                ],

            ])
             ->add('area', 'entity', [
                'class' => 'AppBundle:Area',
                'required' => true,
                'attr' => [
                    'class' => 'select2',
                ],

            ])
              ->add('usuario', 'entity', [
                    'class' => 'UserBundle:UsuarioTrabajador',
                    'required' => true,
                    'label' => 'Asignar UsuarioTrabajador',
                    'property' => 'codigoString',
                    'attr' => [
                        'class' => 'select2',
                        'help_text' => 'Seleccione los usuarios que realizarán las horas',
                    ],
                    'empty_value' => 'Seleccionar Usuario asignado a realizar esta actividad',
                    'required' => true,
                    'multiple' => false,

            ])
             ->add('horaspresupuestadas', null, [
                'label' => 'Horas Presupuestadas',
                'required' => true,
            ])

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RegistroHorasPresupuesto',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_registrohoraspresupuesto';
    }
}
