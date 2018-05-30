<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DepartamentoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreDepartamento', TextType::class, [
                'label' => 'Nombre del departamento*',
                'required' => true,
            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción del departamento (opcional)',
                'required' => false,
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Departamento',
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'userbundle_departamento';
    }
}
