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

class RegistroHorasType extends AbstractType
{
    private $usuario;
    private $area = 1;

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->user = $options['user'];
        $builder
             ->add('proyectoPresupuesto',  EntityType::class, [
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
            ->add('cliente',  EntityType::class, [
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
             ->add('area', EntityType::class, [
                'class' => Area::class,
                'required' => true,
                'label' => 'Área',
                'placeholder' => 'Seleccione el área',
                'mapped' => false,
                'attr' => [
                    'class' => 'select2',
                ],
            ])
            ->add('horasInvertidas', null, [
                'label' => 'Horas invertidas',
                'required' => true,
            ])
            ->add('horasExtraordinarias', CheckboxType::class, [
                'required' => false,
                'label' => 'Las horas realizadas fueron extraordinarias',
            ])
            ->add('ingresadoPor', EntityType::class, [
                'class' => UsuarioTrabajador::class,
                'choice_label' => 'codigoString',
                'data' => $this->usuario,
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

        $formModifier = function (FormInterface $form, $area) {
            $this->area = $form['area']->getData();
            if (gettype($this->area) !== 'integer') {
                $this->area = 1;
            }

            $form->add('actividad', EntityType::class, [
                'class' => Actividad::class,
                'required' => true,
                'placeholder' => 'Seleccione la actividad',
                'attr' => [
                    'class' => 'select2',
                ],
                'query_builder' => function (EntityRepository $er) {
                  return $er->createQueryBuilder('actividad')
                        ->where('actividad.area = :area')
                        ->orderBy('actividad.nombre', 'ASC')
                        ->setParameter('area', $this->area);
                }
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($formModifier) {
                $form = $event->getForm();

                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();

                $this->area = $form['area'];
                
                // this would be your entity, i.e. 
                $data = $event->getData();
                
                $formModifier($event->getForm(), $data);
            }
        );

 
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
