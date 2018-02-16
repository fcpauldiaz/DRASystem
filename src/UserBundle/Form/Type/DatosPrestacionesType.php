<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use UserBundle\Entity\UsuarioTrabajador;

class DatosPrestacionesType extends AbstractType
{
    private $usuario;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->usuario = $options['user'];
        $builder
            ->add('sueldo', MoneyType::class, [
                'currency' => 'GTQ',
                'label' => 'Sueldo*',
                'attr' => [
                    'placeholder' => 'Sueldo Base',
                    'class' => 'form-control input-lg',
                ],
                'grouping' => true,

            ])
            ->add('bonificacionIncentivo', MoneyType::class, [
                'currency' => 'GTQ',
                'label' => 'Bonificación Ley*',
                'attr' => [
                    'placeholder' => 'Bonificación Ley',
                    'class' => 'form-control input-lg',
                ],
                'required' => true,
                'grouping' => true,

            ])
            ->add('otraBonificacion', MoneyType::class, [
                'currency' => 'GTQ',
                'label' => 'Otra bonificación',
                'attr' => [
                    'placeholder' => 'Otra bonificación',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
                'grouping' => true,
            ])
            ->add('gasolina', MoneyType::class, [
                'currency' => 'GTQ',
                'label' => 'Gasolina',
                'attr' => [
                    'placeholder' => 'Gasolina',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
                'grouping' => true,
            ])

            ->add('otrasPrestaciones', MoneyType::class, [
                'currency' => 'GTQ',
                'label' => 'Otras prestaciones',
                'attr' => [
                    'placeholder' => 'Otras prestaciones',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
            ])
            ->add('viaticos', MoneyType::class, [
                'currency' => 'GTQ',
                'label' => 'Viáticos',
                'attr' => [
                    'placeholder' => 'Viáticos',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
            ])
            ->add('otros', MoneyType::class, [
                'currency' => 'GTQ',
                'label' => 'Otros',
                'attr' => [
                    'placeholder' => 'Otros',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
                'grouping' => true,
            ])
            ->add('depreciacion', MoneyType::class, [
                'currency' => 'GTQ',
                'label' => 'Depreciación',
                'attr' => [
                    'placeholder' => 'Depreciación',
                    'class' => 'form-control input-lg',
                ],
                'required' => false,
                'grouping' => true,
            ])
           ->add('usuario', EntityType::class, [
                'class' => UsuarioTrabajador::class,
                'data' => $this->usuario,
                'attr' => [
                    'class' => 'select2',
                ],
            ])
            ->addEventListener(FormEvents::POST_SET_DATA, [$this, 'onPreData'])

        ;

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            [$this, 'onPostData']
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\DatosPrestaciones',
            'user' => null
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'userbundle_datosprestaciones';
    }
    /**
     * Pre Set Data from depending the current status
     * of gastos indirectos.
     *
     * @param FormEvent $event
     */
    public function onPreData(FormEvent $event)
    {
        $form = $event->getForm();
        $gasto = $form->getData()->getGastosIndirectos();

        // Check whether the user has chosen to display his email or not.
        // If the data was submitted previously, the additional value that is
        // included in the request variables needs to be removed.
        if (null === $gasto || $gasto <= 0) {
            $form->add('gastos', CheckboxType::class, [
                'label' => 'Aplica gastos indirectos de Q480 semanales',
                'required' => false,
                'mapped' => false,
            ]);
        } else {
            $form->add('gastosIndirectos', NumberType::class, [
                'label' => 'Gastos Indirectos',
            ]);
        }
    }

    /**
     * Forma de validar el correo de un catedrático.
     *
     * @param FormEvent $event Evento después de mandar la información del formulario
     */
    public function onPostData(FormEvent $event)
    {
        $datosPrestaciones = $event->getData();

        if (isset($event->getForm()['gastos'])) {
            if ($event->getForm()['gastos']->getData() === true) {
                $datosPrestaciones->setGastosDRA();
            }
        }
        $datosPrestaciones->calcularPrestaciones();
    }
}
