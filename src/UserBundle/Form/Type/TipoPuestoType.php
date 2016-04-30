<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints;

class TipoPuestoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombrePuesto', 'text', [
                'label' => 'Nombre del Puesto*',
                'required' => true,
                'constraints' => [
                    new Callback([$this, 'validarNombre']),
                ],
            ])
            ->add('descripcion', 'textarea', [
                'label' => 'Descripción del tipo (opcional)',
                'required' => false,

            ])
            ->add('puestos', 'entity', [
                'class' => 'UserBundle:TipoPuesto',
                'multiple' => true,
                'label' => 'Jerarquía de puestos',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Jerarquía de puestos',
                    'class' => 'form-control input-lg',
                    'width' => '100%',
                    'help_text' => 'En este campo se seleccionan todos los tipos de puestos sobre los cuales se tiene jerarquía.  '.
                        'Por ejemplo: si se crea un tipo puesto gerente, se coloca aquí asistente, encargado y supervisor',
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
            'data_class' => 'UserBundle\Entity\TipoPuesto',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'userbundle_tipopuesto';
    }

    /**
     * Validar los tipo de puestos.
     *
     * @param nombre                    $data    nombre del tipo puesto
     * @param ExecutionContextInterface $context
     */
    public function validarNombre($nombre, ExecutionContextInterface $context)
    {
        $valid_role1 = 'gerente';
        $valid_role2 = 'asistente';
        $valid_role3 = 'encargado';
        $valid_role4 = 'supervisor';
        $nombre = strtolower($nombre);
        if (strpos($nombre, $valid_role1) === false
            &&
            strpos($nombre, $valid_role2) === false
            &&
            strpos($nombre, $valid_role3) === false
            &&
            strpos($nombre, $valid_role4) === false

            ) {
            $context->buildViolation('El tipo de puesto tiene que contener la palabra asistete, encargado, supervisor o gerente')
                ->addViolation();
        }
    }
}
