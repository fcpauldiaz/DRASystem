<?php

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use AppBundle\Entity\RegistroHoras;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\ProyectoPresupuesto;
use AppBundle\Entity\Cliente;
use UserBundle\Entity\UsuarioTrabajador;
use AppBundle\Entity\Actividad;
use AppBundle\Entity\Area;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Braincrafted\Bundle\BootstrapBundle\Form\Type\BootstrapCollectionType;

class RegistroHorasType extends AbstractType
{
    private $user;
    private $area = 1;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->user = $options['user'];
        $builder
             ->add('proyectoPresupuesto', EntityType::class, [
                'class' => ProyectoPresupuesto::class,
                'required' => false,
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
                'choice_label' => 'showSearchParams',
                'placeholder' => 'Seleccione el cliente',
                 'attr' => [
                    'class' => 'select2',
                ],
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cliente')
                      ->orderBy('cliente.razonSocial', 'ASC');
                },

            ])
            ->add('horasActividad', BootstrapCollectionType::class, [
                    'entry_type' =>RegistroHorasActividadHorasType::class,
                    'entry_options' => ['user' => $this->user],
                    'label' => 'Registro de Actividad y Horas',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'add_button_text' => 'Agregar Actividad',
                    'delete_button_text' => 'Eliminar Actividad',
                    'sub_widget_col' => 6,
                    'button_col' => 12,
                    'by_reference' => false, //esta linea también es importante para que se guarde la ref
                    'attr' => [
                         'class' => 'select2',
                    ],

                ])
            ->add('ingresadoPor', EntityType::class, [
                'class' => UsuarioTrabajador::class,
                'choice_label' => 'codigoString',
                'data' => $this->user,
                'attr' => [
                    'help_text' => 'AS para asistente, EN para encargado, SU para supervisor, GE para gerente, SC para socio',
                ],
                'label' => 'Horas realizadas por',
                 'attr' => [
                    'class' => 'select2 disabled',
                    'disabled' => 'disabled'
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
            'data_class' => RegistroHoras::class,
            'user' => null
        ));
        $resolver->setRequired('user'); // Requires that currentOrg be set by the caller.
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_registrohoras';
    }
}
