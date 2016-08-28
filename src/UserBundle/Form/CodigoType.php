<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints;
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
            ])
            ->add('nombres')
            ->add('apellidos')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\Codigo',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'userbundle_codigo';
    }

    public function validarCodigo($codigo, ExecutionContextInterface $context)
    {
        if (strpos($codigo, 'ML') === false
            && strpos($codigo, 'AUD') === false
            && strpos($codigo, 'OD') === false
            && strpos($codigo, 'DM') === false
            ) {
            $context->buildViolation('El código debe de tener AUD, OD, DM, o ML')
                ->atPath('fos_user_register')
                ->addViolation();
        }
    }
}
