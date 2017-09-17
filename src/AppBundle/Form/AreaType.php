<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AreaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('departamento', 'entity', [
                'label' => 'Departamento*',
                'required' => true,
                'class' => 'UserBundle:Departamento',
                'attr' => [
                    'class' => 'select2'
                ]

            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Area'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_area';
    }
}
