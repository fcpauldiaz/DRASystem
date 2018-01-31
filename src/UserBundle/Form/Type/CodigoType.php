<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CodigoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo', null, [
                'label' => 'Código',
                'constraints' => [
                    new Callback([$this, 'validarCodigo']),
                ],
                'attr' => [
                    'placeholder' => 'Formato: Abreviatura Socio - Abreviatura Departamento  Correlativo ',
                    'help_text' => 'Ej: ML-AUD145 : Significa que el socio es Marco Livio del departamento de Auditoría con Correlativo 145',
                ],

            ])
            ->add('nombres')
            ->add('apellidos')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Codigo',
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'userbundle_codigo';
    }

    public function validarCodigo($codigo, ExecutionContextInterface $context)
    {
        if (strpos($codigo, 'ML') === false
            && strpos($codigo, 'AUD') === false
            && strpos($codigo, 'OD') === false
            && strpos($codigo, 'DM') === false
             && strpos($codigo, 'CONTA') === false
            ) {
            $context->buildViolation('El código debe de tener CONTA, AUD, OD, DM, o ML')
                ->atPath('fos_user_register')
                ->addViolation();
        }
    }
}
