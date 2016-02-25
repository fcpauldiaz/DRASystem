<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistroHorasType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('fechaHoras', 'date', [
                'label' => 'Fecha de las horas invertidas',
                'input' => 'datetime',
                'widget' => 'choice',
                'model_timezone' => 'America/Guatemala',
                'view_timezone' => 'America/Guatemala',
                'format' => 'dd-MMM-yyyy',
                'data' => new \DateTime(),
                'attr' => [
                    

                ],
                
            ])
           ->add('usuarios', 'bootstrap_collection', [
                    'type' => 'entity',
                    'label' => 'Agregar usuarios',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'add_button_text' => 'Agregar Usuario involucrado',
                    'delete_button_text' => 'Eliminar Usuario',
                    'sub_widget_col' => 9,
                    'button_col' => 3,
                    'attr' => [
                            'class' => 'select2',
                        ],
                    'options' => [
                       'empty_value' => 'Seleccionar Usuario',
                        'class' => 'UserBundle:Usuario',
                        'required' => true,
                        'label' => 'Buscador de Usuarios',
                        'property' => 'codigoString',
                        'attr' => [
                            'class' => 'select2',
                        ],
                    ],
                ])
           
            ->add('horasInvertidas')
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RegistroHoras',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_registrohoras';
    }
}
