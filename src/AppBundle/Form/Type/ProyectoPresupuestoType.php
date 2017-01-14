<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints;
use Doctrine\ORM\EntityRepository;

class ProyectoPresupuestoType extends AbstractType
{
    private $usuario;

    //única forma que encontré para guardar el campo ingresado por
    //porque los formularios embedded no pasan por el controller
    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    /* @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombrePresupuesto', null, [
                'label' => 'Nombre del presupuesto*',
                'required' => true,
                'attr' => [
                  'placeholder' => 'Nombre cliente - año',
                ],

            ])
            ->add('honorarios', 'money', [
                'required' => false,
                'label' => 'Honorarios (opcional)',
                'attr' => [
                    'help_text' => 'Si ya se tiene designado los honorarios del presupuesto',
                    'placeholder' => 'Honorarios profesionales',
                ],
                'currency' => 'GTQ',
                'grouping' => true,

            ])
            ->add('clientes', 'entity', [
                'class' => 'AppBundle:Cliente',
                'attr' => [
                    'class' => 'select2',
                ],
                'multiple' => false,
                'required' => false,
                'label' => 'Cliente a consolidar (opcional)',
                'empty_value' => 'Cliente a consolidar',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cliente')
                        ->innerJoin('AppBundle:AsignacionCliente', 'asignacion', 'with', 'cliente.id = asignacion.cliente')
                        ->where('asignacion.usuario = :usuario')
                        ->OrWhere('asignacion.usuario = 1')
                        ->setParameter('usuario', $this->usuario);
                },
            ])
            ->add('presupuestoIndividual', 'bootstrap_collection', [
                    'type' => new RegistroHorasPresupuestoType($this->usuario),
                    'label' => 'Registro Horas Presupuesto',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'add_button_text' => 'Agregar Registro',
                    'delete_button_text' => 'Eliminar Registro',
                    'sub_widget_col' => 10,
                    'button_col' => 12,
                    'by_reference' => false, //esta linea también es importante para que se guarde la ref
                    'cascade_validation' => true,
                    'attr' => [
                            'class' => 'select2',
                        ],

                ])
        ;
    }
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ProyectoPresupuesto',
            'constraints' => new Callback([$this, 'validarActividades']),
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_proyectopresupuesto';
    }

    public function checkArray($array, $id1, $id2)
    {
        foreach ($array as $innerArray) {
            if ($innerArray[0] == $id1 && $innerArray[1] == $id2) {
                return true;
            }
        }

        return false;
    }
}
