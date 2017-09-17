<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use UserBundle\Entity\Usuario;
use Doctrine\ORM\EntityRepository;

class RegistroHorasPresupuestoType extends AbstractType
{
    private $usuario;

    public function __construct(Usuario $usuario = null)
    {
        $this->usuario = $usuario;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('cliente', 'entity', [
                'class' => 'AppBundle:Cliente',
                'required' => true,
                'property' => 'showSearchParams',
                'attr' => [
                    'class' => 'select2',
                ],
                'empty_value' => 'Seleccionar cliente',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cliente')
                        ->innerJoin('AppBundle:AsignacionCliente', 'asignacion', 'with', 'cliente.id = asignacion.cliente')
                        ->where('asignacion.usuario = :usuario')
                        ->OrWhere('asignacion.usuario = 1')
                        ->setParameter('usuario', $this->usuario);
                },

            ])
             ->add('actividad', 'entity', [
                'class' => 'AppBundle:Actividad',
                'required' => true,
                'label' => 'Área',
                'empty_value' => 'Seleccionar área a presupuestar',
                'attr' => [
                    'class' => 'select2',
                ],

            ])
             ->add('usuario', 'entity', [
                    'class' => 'UserBundle:UsuarioTrabajador',
                    'required' => true,
                    'label' => 'Asignar Usuario',
                    'property' => 'codigoString',
                    'attr' => [
                        'class' => 'select2',
                        'help_text' => 'Seleccione el usuario que realizará las horas',
                    ],
                    'empty_value' => 'Seleccionar Usuario asignado a realizar esta actividad',
                    'multiple' => false,

            ])
            ->add('horasPresupuestadas')

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
