<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ContactoClienteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombreContacto', null, [
                'attr' => [
                    'placeholder' => 'Nombre/s Contacto'
                ]
            ])
            ->add('apellidosContacto', null, [
                'attr' => [
                    'placeholder' => 'Apellidos Contacto'
                ]
            ])
              ->add('puesto', null, [
                'attr' => [
                    'placeholder' => 'Puesto'
                ]
            ])
           ->add('telefonoContacto', 'bootstrap_collection', [
            'allow_add' => true,
            'allow_delete' => true,
            'add_button_text' => 'Agregar Teléfono',
            'delete_button_text' => 'Eliminar Teléfono',
            'sub_widget_col' => 9,
            'button_col' => 3,
            'type' => TextType::class,
            // these options are passed to each "email" type
            'options' => array(
                'required' => false,
                   'attr' => [
                    'placeholder' => 'Ingresar el teléfono sin guión',
                ],

            ),
            'label' => 'Teléfono',
        ])
           ->add('correoContacto', 'bootstrap_collection', [
            'allow_add' => true,
            'allow_delete' => true,
            'add_button_text' => 'Agregar Correo',
            'delete_button_text' => 'Eliminar Correo',
            'sub_widget_col' => 9,
            'button_col' => 3,
            'type' => EmailType::class,
            // these options are passed to each "email" type
            'options' => array(
                'required' => false,

            ),
            'label' => 'Correos',
        ])

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ContactoCliente',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_contactocliente';
    }
}
