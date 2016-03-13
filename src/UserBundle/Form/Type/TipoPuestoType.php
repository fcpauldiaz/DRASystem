<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TipoPuestoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombrePuesto','text',[
                'label' => 'Nombre del Puesto*',
                'required' => true,
            ])
            ->add('descripcion','textarea',[
                'label' => 'Descripción del tipo (opcional)',
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
            'data_class' => 'UserBundle\Entity\TipoPuesto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'userbundle_tipopuesto';
    }
}
