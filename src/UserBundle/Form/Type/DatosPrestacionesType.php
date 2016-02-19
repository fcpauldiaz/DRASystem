<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DatosPrestacionesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sueldo')
            ->add('bonificacionIncentivo')
            ->add('bonificacionLey')
            ->add('gasolina')
            ->add('prestacionesSobreSueldo')
            ->add('otrasPrestaciones')
            ->add('otros')
            ->add('depreciacion')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\DatosPrestaciones',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'userbundle_datosprestaciones';
    }
}
