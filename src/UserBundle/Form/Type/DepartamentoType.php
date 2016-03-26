<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DepartamentoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreDepartamento', 'text', [
                'label' => 'Nombre del departamento*',
                'required' => true,
            ])
            ->add('descripcion', 'textarea', [
                'label' => 'Descripción del departamento (opcional)',
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
            'data_class' => 'UserBundle\Entity\Departamento',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'userbundle_departamento';
    }
}
