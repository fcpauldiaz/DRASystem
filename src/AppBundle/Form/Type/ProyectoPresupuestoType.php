<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints;

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

            ])
            ->add('honorarios', 'money', [
                'required' => false,
                'label' => 'Honorarios (opcional)',
                'attr' => [
                    'help_text' => 'Si ya se tiene designado los honorarios del presupuesto',
                ],
                'currency' => 'GTQ',
                'grouping' => true,

            ])
              ->add('socios', 'entity', [
                'required' => false,
                'label' => 'Socio/s asignados',
                'class' => 'UserBundle:UsuarioSocio',
                'multiple' => true,

            ])
            ->add('gerentes', 'entity', [
                'required' => false,
                'label' => 'Gerentes/s asignados',
                'class' => 'UserBundle:UsuarioTrabajador',
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('usuario')
                        ->leftJoin('usuario.puestos', 'puesto')
                        ->leftJoin('puesto.tipoPuesto', 'tipopuesto')
                        ->where('tipopuesto.nombrePuesto LIKE :nombre')
                        ->setParameter('nombre', '%Gerente%');
                },

            ])
            ->add('presupuestoIndividual', 'bootstrap_collection', [
                    'type' => new RegistroHorasPresupuestoType($this->usuario),
                    'label' => 'Registro Horas Presupuesto',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'add_button_text' => 'Agregar Registro',
                    'delete_button_text' => 'Eliminar Registro',
                    'sub_widget_col' => 6,
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

    /**
     * Validar que no se repitan las actividades dentro un mismo presupuesto.
     *
     * @param Array                     $data    contiene los datos del formulario
     * @param ExecutionContextInterface $context
     */
    public function validarActividades($data, ExecutionContextInterface $context)
    {
        $registrosPresupuesto = $data->getPresupuestoIndividual();
        $actividades = [];
        foreach ($registrosPresupuesto as $registro) {
            $actividadActual = $registro->getActividad()->getId();
            $usuario = $registro->getUsuario()->getId();

            if ($this->checkArray($actividades, $actividadActual, $usuario) === true) {
                $context->buildViolation('Error: no se deben repetir las actividades por usuario en un presupuesto')
                    ->atPath('proyectopresupuesto_new')
                    ->addViolation();
            }
            $actividades[] = [$actividadActual, $usuario];
        }
    }

    public function checkArray($array, $id1, $id2)
    {
        $i = 0;
        foreach ($array as $innerArray) {
            if ($innerArray[0] == $id1 && $innerArray[1] == $id2) {
                return true;
            }
        }

        return false;
    }
}
