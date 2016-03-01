<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClienteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nit', null, [
                'label' => 'NIT *',
                'required' => true,
            ])
            ->add('numeroContrato', null, [
                'label' => 'Número de Contrato (opcional)',
                'required' => false,
            ])
            ->add('razonSocial', null, [
                'label' => 'Razón Social *',
                'required' => true,
            ])
            ->add('nombreComercial', null, [
                'required' => false,
                'label' => 'Nombre Comercial (opcional)',
            ])
            ->add('nombreCorto', null, [
                'required' => false,
                'label' => 'Nombre Corto (opcional)',
                ])
            ->add('serviciosPrestados', 'textarea', [
                'label' => 'Servicios Prestados*',
                'required' => true,
            ])
            ->add('codigoSAT', null, [
                'label' => 'Código SAT (opcional)',
                'required' => false,
            ])
            ->add('contactos', null, [

                'label' => 'Información de Contacto',
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
            'data_class' => 'AppBundle\Entity\Cliente',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_cliente';
    }
}
