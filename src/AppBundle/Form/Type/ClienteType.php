<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints;

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
                'attr' => [
                    'placeholder' => 'Ingresar el NIT sin guión',
                ],
                'constraints' => [
                    new Callback([$this, 'validarNIT']),
                ],

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
                'attr' => [
                    'class' => 'select2',
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

     /* Validar que el NIT no tenga guiones
     *
     * @param Array                     $data    contiene los datos del formulario
     * @param ExecutionContextInterface $context
     */
    public function validarNIT($nit, ExecutionContextInterface $context)
    {
        if (strpos($nit, '-') !== false) {
            $context->buildViolation('El NIT no puede tener guión')
                ->atPath('cliente_new')
                ->addViolation();
        }
    }
}
