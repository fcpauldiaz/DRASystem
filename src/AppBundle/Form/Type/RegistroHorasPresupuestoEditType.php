<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use UserBundle\Entity\Usuario;

class RegistroHorasPresupuestoEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('cliente', 'entity', [
                'class' => 'AppBundle:Cliente',
                'required' => true,

            ])
             ->add('actividad', 'entity', [
                'class' => 'AppBundle:Actividad',
                'required' => true,

            ])
              ->add('usuario', 'entity', [
                    'class' => 'UserBundle:Usuario',
                    'required' => true,
                    'label' => 'Asignar Usuario',
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
