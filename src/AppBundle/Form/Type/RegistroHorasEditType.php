<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\ProyectoPresupuesto;
use AppBundle\Entity\Cliente;
use AppBundle\Entity\Actividad;
use UserBundle\Entity\UsuarioTrabajador;

class RegistroHorasEditType extends AbstractType
{
    private $usuario;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->usuario = $options['usuario'];
        $builder
             ->add('proyectoPresupuesto', EntityType::class, [
                'class' => ProyectoPresupuesto::class,
                'required' => true,
                'placeholder' => 'Seleccione el presupuesto asignado',
                'attr' => [
                    'class' => 'select2',
                ],

            ])
             ->add('fechaHoras', DateType::class, [
                'label' => 'Fecha de las horas invertidas',
                'input' => 'datetime',
                'widget' => 'choice',
                'model_timezone' => 'America/Guatemala',
                'view_timezone' => 'America/Guatemala',
                'format' => 'dd-MMM-yyyy',
                'data' => new \DateTime(),
                'attr' => [

                ],
                 'required' => true,

            ])
            ->add('cliente', EntityType::class, [
                'class' => Cliente::class,
                'required' => true,
                'placeholder' => 'Seleccione el cliente',
                'attr' => [
                    'class' => 'select2',
                ],

            ])
             ->add('actividad', EntityType::class, [
                'class' => Actividad::class,
                'required' => true,
                'placeholder' => 'Seleccione la actividad',
                'attr' => [
                    'class' => 'select2',
                ],
            ])
            ->add('horasInvertidas', null, [
                'label' => 'Horas invertidas',
                'required' => true,
            ])
            ->add('ingresadoPor', EntityType::class, [
                'class' => UsuarioTrabajador::class,
                'choice_label' => 'codigoString',
                'data' => $this->usuario,
                'attr' => [
                    'help_text' => 'AS para asistente, EN para encargado, SU para supervisor, GE para gerente, SC para socio',
                    'class' => 'select2',
                ],

            ])
             ->add('horasExtraordinarias', null, [
                'label' => 'Horas Extraordinarias',
                'attr' => [
                    'help_text' => 'Marque esta opción si las horas que está ingresando son extraordinarias',
                ],
            ])

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\RegistroHoras',
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_registrohoras';
    }
}
