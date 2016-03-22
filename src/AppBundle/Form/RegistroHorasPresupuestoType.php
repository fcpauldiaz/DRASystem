<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistroHorasPresupuestoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
              $builder

         
            ->add('cliente', 'entity', [
                'class' => 'AppBundle:Cliente',
                'required' => true,

            ])
             ->add('actividad', 'entity', [
                'class' => 'AppBundle:Actividad',
                'required' => true,


            ])
             ->add('horaspresupuestadas',null,[
                'label' => 'Horas Prespuestadas',
                'required' => true,
            ])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RegistroHorasPresupuesto'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_registrohoraspresupuesto';
    }
}
