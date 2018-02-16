<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use UserBundle\Entity\Permiso;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TipoPuestoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombrePuesto', TextType::class, [
                'label' => 'Nombre del Puesto*',
                'required' => true,

            ])
            ->add('descripcion', TextareaType::class, [
                'label' => 'Descripción del tipo (opcional)',
                'required' => false,

            ])
            ->add('permisos', EntityType::class, [
                'class' => Permiso::class,
                'expanded' => true,
                'multiple' => true,
                ])

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\TipoPuesto',
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
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
