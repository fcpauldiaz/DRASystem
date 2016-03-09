<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ActividadType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre',null,[
                'required' => true,
                'label' => 'Nombre de la actividad *'
            ])
            ->add('descripcion','textarea',[
                'label' => 'Descripción *',
            ])
            ->add('abreviatura',null,[
                'label' => 'Abreviatura (opcional)',
                'required' => false,
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Actividad',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_actividad';
    }
}
