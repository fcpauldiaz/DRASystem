<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Actividad;

class RegistroActividadHorasType extends AbstractType
{
    private $usuario;

    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('actividad', EntityType::class, [
                'class' => Actividad::class,
                'required' => true,
                'empty_value' => 'Seleccione la actividad',
                'attr' => [
                    'class' => 'select2',
                ],
            ])
            ->add('horasInvertidas', null, [
                'label' => 'Horas invertidas',
                'required' => true,
            ])
            ->add('horasExtraordinarias', CheckboxType::class, [
                'required' => false,
                'label' => 'Las horas realizadas fueron extraordinarias',
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_registrohoras_actividad_horas';
    }
}
