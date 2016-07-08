<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class DatosPrestacionesType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sueldo', 'money', [
                'currency' => 'GTQ',
                'label' => 'Sueldo*',
                'attr' => [
                    'placeholder' => 'Sueldo Base',
                    'class' => 'form-control input-lg',
                ],
                'grouping' => true,

            ])
            ->add('bonificacionIncentivo', 'money', [
                'currency' => 'GTQ',
                'label' => 'Otra Bonificación*',
                'attr' => [
                    'placeholder' => 'Otra Bonificación',
                    'class' => 'form-control input-lg',
                ],
                'required' => true,
                'grouping' => true,

            ])
            ->add('otraBonificacion', 'money', [
                'currency' => 'GTQ',
                'label' => 'Bonificación Ley',
                'attr' => [
                    'placeholder' => 'Bonificación Ley',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
                'grouping' => true,
            ])
            ->add('gasolina', 'money', [
                'currency' => 'GTQ',
                'label' => 'Gasolina',
                'attr' => [
                    'placeholder' => 'Gasolina',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
                'grouping' => true,
            ])

            ->add('cargosIndirectos', 'money', [
                'currency' => 'GTQ',
                'label' => 'Otras prestaciones',
                'attr' => [
                    'placeholder' => 'Otras prestaciones',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
            ])
              ->add('viaticos', 'money', [
                'currency' => 'GTQ',
                'label' => 'Viáticos',
                'attr' => [
                    'placeholder' => 'Viáticos',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
            ])
            ->add('otros', 'money', [
                'currency' => 'GTQ',
                'label' => 'Otros',
                'attr' => [
                    'placeholder' => 'Otros',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
                'grouping' => true,
            ])
            ->add('depreciacion', 'money',  [
                'currency' => 'GTQ',
                'label' => 'Depreciación',
                'attr' => [
                    'placeholder' => 'Depreciación',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
                'grouping' => true,
            ])
        ;

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            [$this, 'onPostData']
        );
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

    /**
     * Forma de validar el correo de un catedrático.
     *
     * @param FormEvent $event Evento después de mandar la información del formulario
     */
    public function onPostData(FormEvent $event)
    {
        $datosPrestaciones = $event->getData();
        $datosPrestaciones->calcularPrestaciones();
       
    }
}
