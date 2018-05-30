<?php

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Actividad;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use AppBundle\Entity\Cliente;
use UserBundle\Entity\UsuarioTrabajador;
use AppBundle\Entity\Area;

class RegistroHorasActividadHorasType extends AbstractType
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
            ->add('actividad', EntityType::class, [
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
            ])
            ->add('horasInvertidas', NumberType::class, [
                'label' => 'Horas invertidas',
                'required' => true,
            ])
            ->add('horasExtraordinarias', CheckboxType::class, [
              'required' => false,
              'label' => 'Las horas realizadas fueron extraordinarias',
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
            'data_class' => null,
            'user' => null
        ));
        $resolver->setRequired('user'); // Requires that
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_registrohoras_actividad_horas';
    }
}
