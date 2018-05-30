<?php

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\Usuario;
use AppBundle\Entity\RegistroHorasPresupuesto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Cliente;
use AppBundle\Entity\Area;
use UserBundle\Entity\UsuarioTrabajador;

class RegistroHorasPresupuestoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->usuario = $options['user'];
        $builder

            ->add('cliente', EntityType::class, [
                'class' => Cliente::class,
                'required' => true,
                'choice_label' => 'showSearchParams',
                'attr' => [
                    'class' => 'select2',
                ],
                'placeholder' => 'Seleccionar cliente',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cliente')
                        ->innerJoin('AppBundle:AsignacionCliente', 'asignacion', 'with', 'cliente.id = asignacion.cliente')
                        ->where('asignacion.usuario = :usuario')
                        ->OrWhere('asignacion.usuario = 1')
                        ->setParameter('usuario', $this->usuario);
                },

            ])
             ->add('area', EntityType::class, [
                'class' => Area::class,
                'required' => true,
                'label' => 'Área',
                'placeholder' => 'Seleccionar área a presupuestar',
                'attr' => [
                    'class' => 'select2',
                ],

            ])
             ->add('usuario', EntityType::class, [
                    'class' => UsuarioTrabajador::class,
                    'required' => true,
                    'label' => 'Asignar Usuario',
                    'choice_label' => 'codigoString',
                    'attr' => [
                        'class' => 'select2',
                        'help_text' => 'Seleccione el usuario que realizará las horas',
                    ],
                    'placeholder' => 'Seleccionar Usuario asignado a realizar esta actividad',
                    'multiple' => false,

            ])
            ->add('horasPresupuestadas')

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => RegistroHorasPresupuesto::class,
            'user' => null
        ));
        $resolver->setRequired('user');
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_registrohoraspresupuesto';
    }
}
