<?php

namespace UserBundle\Form\Type;

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
            ->add('tipoPuesto', 'choice', [
                'choices' => [
                    'Asistente' => 'Asistente',
                    'Supervisor' => 'Supervisor',
                    'Gerente' => 'Gerente',
                ],
                // *this line is important*
                'choices_as_values' => true,
                'attr' => [
                    'class' => 'select2 form-control input-lg',
                ],

            ])
            ->add('nombrePuesto', null, [
                'label' => 'Departamento',
                'attr' => [
                    'class' => 'form-control input-lg',
                ],
            ])
             ->add('date', 'date', [
                'label' => 'Fecha',
                'input' => 'datetime',
                'widget' => 'choice',
                'model_timezone' => 'America/Guatemala',
                'view_timezone' => 'America/Guatemala',
                'format' => 'dd-MMM-yyyy',
                'data' => new \DateTime(),
                'attr' => [
                    'class' => 'select2',

                ],
                'years' => [
                    1980, 1981, 1982, 1983, 1984, 1985, 1986, 1987, 1988, 1989, 1990,
                    1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998, 1999, 2000, 2001,
                    2002, 2003, 2004, 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012,
                    2013, 2014, 2015, 2016, 2017, 2018, 2019, 2020, 2021, 2022, 2023,
                    2024, 2025, 2026, 2027, 2028, 2029, 2030,
                ],

            ])
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
