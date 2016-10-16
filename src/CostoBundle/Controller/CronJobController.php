<?php

namespace CostoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CostoBundle\Entity\Costo;

/**
 * CronJob controller.
 */
class CronJobController extends Controller
{
    /**
     * @Route("costo/calcular/todos", name = "cron_job")
     */
    public function calcularCostoAction(Request $request)
    {
        $form = $this->createFormAction();
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();

        $usuarios = $em->getRepository('UserBundle:UsuarioTrabajador')->findAll();

        $firstDay = new \DateTime('first day of this month');

        $lastDay = new \Datetime('last day of this month');
        if ($form->isValid()) {
            $firstDayRequest = $form->getData()['fechaInicio'];
            $lastDayRequest = $form->getData()['fechaFinal'];
            $usuariosRequest = $form->getData()['usuarios'];

            if ($firstDayRequest !== null) {
                $firstDay  = $firstDayRequest;
                if ($lastDayRequest === null) {
                    $this->addFlash('error', 'No puede dejar solo un campo de fecha en blanco');
                    return $this->render('CostoBundle:Costo:cronJob.html.twig',
                        [
                            'verificador' => true, //mandar variable a javascript
                            'form' => $form->createView(),
                        ]
                    );
                }
            }
            if ($lastDayRequest !== null) {
                if ($firstDayRequest === null) {
                    $this->addFlash('error', 'No puede dejar solo un campo de fecha en blanco');
                    return $this->render('CostoBundle:Costo:cronJob.html.twig',
                        [
                            'verificador' => true, //mandar variable a javascript
                            'form' => $form->createView(),
                        ]
                    );
                }
                $lastDay = $lastDayRequest;
            }
            if ($usuariosRequest !== null && empty($usuariosRequest->toArray()) === false) {
                $usuarios = $usuariosRequest;
            }
        }

        foreach ($usuarios as $usuario) {
            $entidadCosto = new Costo();
            $actualizarCosto = $em->getRepository('CostoBundle:Costo')->findOneBy([
                'fechaInicio' => $firstDay,
                'fechaFinal' => $lastDay,
                'usuario' => $usuario
            ]);
            if ($actualizarCosto !== null) {
                $entidadCosto = $actualizarCosto;
            }
            $entidadCosto->setFechaInicio($firstDay);
            $entidadCosto->setFechaFinal($lastDay);
            $costo = $this->calcularCosto(
                $firstDay,
                $lastDay,
                $usuario
            );

            $entidadCosto->setCosto($costo);
            $entidadCosto->setUsuario($usuario);
            $em->persist($entidadCosto);
        }
        $em->flush();
        $this->addFlash('success', 'Se han guardado los costos');
        return $this->redirect($this->generateUrl('cron_job_view'));
    }

    /**
     * @Route("costo/calcular/view", name = "cron_job_view")
     */
    public function showButtonAction()
    {
       $form = $this->createFormAction();

        return $this->render('CostoBundle:Costo:cronJob.html.twig',
            [
                'verificador' => false,
                'form' => $form->createView(),
            ]
        );
    }

    public function createFormAction()
    {
         $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('cron_job'))
             ->add('usuarios', 'entity', [
                'class' => 'UserBundle:Usuario',
                'required' => false,
                'empty_value' => 'Seleccione el usuario',
                 'attr' => [
                    'class' => 'select2',
                ],
                'multiple' => true,
                'label' => 'Usuarios (opcional)'

            ])
            ->add('fechaInicio', 'collot_datetime', ['pickerOptions' => [
                'format' => 'dd/mm/yyyy',
                'weekStart' => 0,
                'autoclose' => true,
                'startView' => 'year',
                'minView' => 'year',
                'maxView' => 'decade',
                'todayBtn' => false,
                'todayHighlight' => true,
                'keyboardNavigation' => true,
                'language' => 'es',
                'forceParse' => false,
                'minuteStep' => 5,
                'pickerReferer ' => 'default', //deprecated
                'pickerPosition' => 'bottom-right',
                'viewSelect' => 'month',
                'showMeridian' => false,
            ],
            'attr' => [
                'class' => 'hide-element fecha-inicial',
            ],
            'label_attr' => [
                'class' => 'hide-element',
            ],
            'label' => 'Fecha Inicial (opcional)',
            'read_only' => true,
            'required' => false,
            ])
           ->add('fechaFinal', 'collot_datetime', ['pickerOptions' => [
                    'format' => 'dd/mm/yyyy',
                    'weekStart' => 0,
                    'autoclose' => true,
                    'startView' => 'year',
                    'minView' => 'year',
                    'maxView' => 'decade',
                    'todayBtn' => false,
                    'todayHighlight' => true,
                    'keyboardNavigation' => true,
                    'language' => 'es',
                    'forceParse' => false,
                    'minuteStep' => 5,
                    'pickerReferer ' => 'default', //deprecated
                    'pickerPosition' => 'bottom-right',
                    'viewSelect' => 'month',
                    'showMeridian' => false,
                ],

                'attr' => [
                    'class' => 'hide-element fecha-final',
                ],
                 'label' => 'Fecha Final (opcional)',
                'label_attr' => [
                    'class' => 'hide-element',
                ],
                'read_only' => true,
                'required' => false,
            ])
            ->add('submit', 'submit', array('label' => 'Calcular'))
            ->getForm();
        return $form;
    }

    private function calcularCosto($fechaInicio, $fechaFin, $usuario)
    {
        $datosPrestaciones = $usuario->getDatosPrestaciones()->last();
        $totalHorasPorPeriodo = 0;
        if ($datosPrestaciones !== false) {
            $totalIngreso = $datosPrestaciones->calcularTotalPrestaciones();
            //ahora busco todas las horas ingresadas por el usuario
            //en el período seleccionado
            $repository = $this->getDoctrine()->getRepository('AppBundle:RegistroHoras');
            $totalHorasPorPeriodo = $repository->createQueryBuilder('registro')
                ->select('SUM(registro.horasInvertidas)')
                ->Where('registro.fechaHoras >= :fechaInicio')
                ->andWhere('registro.fechaHoras <= :fechaFin')
                ->andWhere('registro.ingresadoPor = :usuario')
                ->setParameter('fechaInicio', $fechaInicio)
                ->setParameter('fechaFin', $fechaFin)
                ->setParameter('usuario', $usuario)
                ->getQuery()
                ->getSingleScalarResult();
        }
        $costo = 0;
        if ($totalHorasPorPeriodo != 0) {
            $costo = $totalIngreso / $totalHorasPorPeriodo;
        }

        return $costo;
    }
}
