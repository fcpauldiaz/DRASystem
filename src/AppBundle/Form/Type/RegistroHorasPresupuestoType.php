<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use UserBundle\Entity\Usuario;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class RegistroHorasPresupuestoType extends AbstractType
{
    private $usuario;
    private $collectionUsuario;
    
    public function __construct(Usuario $usuario = null)
    {
        $this->usuario = $usuario;
        $this->collectionUsuario = new \Doctrine\Common\Collections\ArrayCollection();
        $this->collectionUsuario->add($usuario);
    }

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
             ->add('usuariosAsignados', 'entity', [
                    'class' => 'UserBundle:Usuario',
                    'required' => true,
                    'label' => 'Asignación de Usuarios',
                    'property' => 'codigoString',
                    'attr' => [
                        'class' => 'select2',
                        'help_text' => 'Seleccione los usuarios que realizarán las horas'
                    ],
                    'empty_value' => 'Seleccionar Usuario asignado a realizar esta actividad',
                    'multiple' => true,
                    'required' => true,
                    'data' => $this->collectionUsuario,

            ])
             ->add('horaspresupuestadas', null, [
                'label' => 'Horas Presupuestadas',
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
            'data_class' => 'AppBundle\Entity\RegistroHorasPresupuesto',
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
