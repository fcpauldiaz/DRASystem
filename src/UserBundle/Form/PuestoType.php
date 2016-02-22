<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PuestoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tipoPuesto', 'choice', array(
                'choices' => array(
                    'Asistente' => 'Asistente',
                    'Supervisor' => 'Supervisor',
                    'Gerente' => 'Gerente',
                ),
                // *this line is important*
                'choices_as_values' => true,
            ))
            ->add('nombrePuesto')
            ->add('date')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Puesto',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'userbundle_puesto';
    }
}
