<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use UserBundle\Entity\Usuario;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;



class RegistroHorasPresupuestoType extends AbstractType
{
    private $usuario;

    public function __construct(Usuario $usuario = null){
        $this->usuario = $usuario;
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
             ->add('horaspresupuestadas',null,[
                'label' => 'Horas Presupuestadas',
                'required' => true,
            ])
        ;
        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            [$this, 'onPostData']
        );
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

     /**
     * Forma de validar el correo de un catedrático.
     *
     * @param FormEvent $event Evento después de mandar la información del formulario
     */
    public function onPostData(FormEvent $event)
    {
        $registro = $event->getData();
        $registro->setIngresadoPor($this->usuario);
       
    }
}
