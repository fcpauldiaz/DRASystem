<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\AsignacionCliente;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use UserBundle\Entity\Usuario;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\CallbackTransformer;

class ClienteType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nit', null, [
                'label' => 'NIT *',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Ingresar el NIT con guión',
                ],
                'constraints' => [
                    new Callback([$this, 'validarNIT']),
                ],

            ])
            ->add('razonSocial', null, [
                'label' => 'Razón Social *',
                'required' => true,
            ])
            ->add('nombreComercial', null, [
                'required' => false,
                'label' => 'Nombre Comercial (opcional)',
            ])
            ->add('serviciosPrestados', TextareaType::class, [
                'label' => 'Servicios Prestados*',
                'required' => true,
            ])
            ->add('usuarioAsignados', EntityType::class, [
                'class' => Usuario::class,
                'label' => 'Usuario Asignado al cliente *',
                'required' => true,
                'attr' => [
                    'class' => 'select2',
                ],
                'multiple' => true

            ])
        ;
        $builder
          ->get('usuarioAsignados')
          ->addModelTransformer(new CallbackTransformer(
                function ($users) {
                  $array = [];
                  if (is_array($users)) {
                    return $users;
                  }
                  foreach ($users->toArray() as $user) {
                    $array[] = $user;
                  }
                  return $array;
                },
                function ($users) {
                    $asignaciones = [];
                    foreach($users as $user) {
                      $asignacion = new AsignacionCliente($user, null);
                      $asignaciones[] = $asignacion;
                    }
                    return $asignaciones;
                }
          ))
      ;
      $builder->addEventListener(
        FormEvents::PRE_SUBMIT,
        [$this, 'onPostData']
      );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Cliente',
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'appbundle_cliente';
    }

    /* Validar que el NIT tenga guiones
    *
    * @param Array                     $data    contiene los datos del formulario
    * @param ExecutionContextInterface $context
    */
    public function validarNIT($nit, ExecutionContextInterface $context)
    {
        if (strpos($nit, '-') === false) {
            $context->buildViolation('El NIT debe tener guión')
                ->atPath('cliente_new')
                ->addViolation();
        }
    }

    /**
     * Cambiar Usuario a Asignacion Cliente
     *
     * @param FormEvent $event Evento después de mandar la información del formulario
     */
    public function onPostData(FormEvent $event)
    {
        $data = $event->getData();
        $usuarios = $data['usuarioAsignados'];

        $copyUsuarios = $usuarios;
        $data['usuarioAsignados'] = [];
        foreach ($copyUsuarios as $usuario) {
          $asignacion = new AsignacionCliente($usuario, $data);
          $data['usuarioAsignados'] = $asignacion;
        }
    }
}
