<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Area;
use UserBundle\Entity\UsuarioTrabajador;

class RegistroHorasPresupuestoEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('cliente', EntityType::class, [
                'class' => Cliente::class,
                'required' => true,
                'attr' => [
                    'class' => 'select2',
                ],

            ])
             ->add('area', EntityType::class, [
                'class' => Area::class,
                'required' => true,
                'attr' => [
                    'class' => 'select2',
                ],

            ])
              ->add('usuario', EntityType::class, [
                    'class' => UsuarioTrabajador::class,
                    'required' => true,
                    'label' => 'Asignar UsuarioTrabajador',
                    'choice_label' => 'codigoString',
                    'attr' => [
                        'class' => 'select2',
                        'help_text' => 'Seleccione los usuarios que realizarán las horas',
                    ],
                    'placeholder' => 'Seleccionar Usuario asignado a realizar esta actividad',
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
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RegistroHorasPresupuesto',
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_registrohoraspresupuesto';
    }
}
