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
