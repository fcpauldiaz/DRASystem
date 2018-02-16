<?php

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\Cliente;
use Braincrafted\Bundle\BootstrapBundle\Form\Type\BootstrapCollectionType;
use AppBundle\Entity\ProyectoPresupuesto;

class ProyectoPresupuestoType extends AbstractType
{
    private $usuario;

    //única forma que encontré para guardar el campo ingresado por
    //porque los formularios embedded no pasan por el controller


    /* @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->usuario = $options['user'];
        $builder
            ->add('nombrePresupuesto', null, [
                'label' => 'Nombre del presupuesto*',
                'required' => true,
                'attr' => [
                  'placeholder' => 'Nombre cliente - año',
                ],

            ])
            ->add('honorarios', MoneyType::class, [
                'required' => false,
                'label' => 'Honorarios (opcional)',
                'attr' => [
                    'help_text' => 'Si ya se tiene designado los honorarios del presupuesto',
                    'placeholder' => 'Honorarios profesionales',
                ],
                'currency' => 'GTQ',
                'grouping' => true,

            ])
            ->add('clientes', EntityType::class, [
                'class' => Cliente::class,
                'attr' => [
                    'class' => 'select2',
                ],
                'multiple' => true,
                'required' => false,
                'label' => 'Cliente a consolidar (opcional)',
                'placeholder' => 'Cliente a consolidar',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('cliente')
                        ->innerJoin('AppBundle:AsignacionCliente', 'asignacion', 'with', 'cliente.id = asignacion.cliente')
                        ->where('asignacion.usuario = :usuario')
                        ->OrWhere('asignacion.usuario = 1')
                        ->setParameter('usuario', $this->usuario);
                },
            ])
            ->add('presupuestoIndividual', BootstrapCollectionType::class, [
                    'entry_type' =>RegistroHorasPresupuestoType::class,
                    'entry_options' => ['user' => $this->usuario],
                    'label' => 'Registro Horas Presupuesto',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'add_button_text' => 'Agregar Registro',
                    'delete_button_text' => 'Eliminar Registro',
                    'sub_widget_col' => 10,
                    'button_col' => 12,
                    'by_reference' => false, //esta linea también es importante para que se guarde la ref
                    'attr' => [
                            'class' => 'select2',
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
            'data_class' => ProyectoPresupuesto::class,
            'user' => null
        ));
        $resolver->setRequired('user'); 
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
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
