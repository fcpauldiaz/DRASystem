<?php

namespace CostoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CostoBundle\Entity\Costo;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * CronJob controller.
 */
class CronJobController extends Controller
{
    /**
     * @Route("costo/calcular/todos", name = "cron_job_cost")
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
            ->setAction($this->generateUrl('cron_job_cost'))
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

    /**
     * Cron Job to send email for users with 
     * pending hours to be approved
     * @param  Request $request 
     * @return Response
     *
     * @Route("cron/email/hours", name="cron_hours")
     */
    public function cronJobEmailAction(Request $request)
    {
        $qb = $this
            ->getDoctrine()
            ->getRepository('UserBundle:UsuarioTrabajador')
            ->createQueryBuilder('u')
            ->where('u.roles LIKE :roles')
            ->setParameter('roles', '%"'.'ROLE_ASIGNACION'.'"%');
        $usuarios = $qb->getQuery()->getResult();
        foreach($usuarios as $usuario) {
            $horas = $this->queryHorasNoAprobadas($usuario);
        }
        return new JsonResponse('success');
    }

    private function queryHorasNoAprobadas($usuario)
    {
        $qb = $this
            ->getDoctrine()
            ->getRepository('AppBundle:RegistroHoras')
            ->createQueryBuilder('r')
            ->select('r')
            ->innerJoin('UserBundle:Usuario', 'u', 'with', 'r.ingresadoPor = u.id')
            ->innerJoin('UserBundle:UsuarioRelacionado', 'ur', 'with', 'ur.usuarioPertenece = r.ingresadoPor')
            ->where('r.aprobado = false')
            ->andWhere('ur.usr = :user_id')
            ->setParameter('user_id', $usuario->getId());
            return $qb->getQuery()->getResult();
    }

    private function getHorasNoAprobadas($usuario)
    {
        $qb = $this
            ->getDoctrine()
            ->getRepository('AppBundle:RegistroHoras')
            ->createQueryBuilder('r')
            ->innerJoin('r.ingresadoPor', 'ing')
            ->where('r.aprobado = false')
            ->andWhere('ing = :usuario')
            ->setParameter('usuario', $usuario);
        return $qb->getQuery()->getResult();

    }

    /**
     * Función para enviar un correo.
     *
     * @param Usuario $enviado_a Nombre de la persona a la que se le envía el correo
     * @param Array usuarios que no tienen horas aprobadas. 
     */
    private function sendEmail($enviado_a, $usuarios)
    {
        $fromEmail = 'no-responder@newtonlabs.com.gt';

        $message = \Swift_Message::newInstance();

        $mensaje = 'Estimado usuario, las siguientes horas no han sido aprobadas';

        //espacio para agregar imágenes
        $img_src = $message->embed(\Swift_Image::fromPath('images/email_header.png'));//attach image 1
        $fb_image = $message->embed(\Swift_Image::fromPath('images/fb.gif'));//attach image 2
        $tw_image = $message->embed(\Swift_Image::fromPath('images/tw.gif'));//attach image 3
        $right_image = $message->embed(\Swift_Image::fromPath('images/right.gif'));//attach image 4
        $left_image = $message->embed(\Swift_Image::fromPath('images/left.gif'));//attach image 5

        $subject = 'Aprobación de Horas';

        $message
            ->setSubject($subject)
            ->setFrom([$fromEmail => 'Smart-Time'])
            ->setTo($enviado_a->getEmail())
            ->setBody($this->renderView('AppBundle:AprobacionHoras:emailHoras.html.twig', [
                'image_src' => $img_src,
                'fb_image' => $fb_image,
                'tw_image' => $tw_image,
                'right_image' => $right_image,
                'left_image' => $left_image,
                'enviado_a' => $enviado_a,
                'mensaje' => $mensaje,
                'registros' => $registros,
                ]), 'text/html')
            ->setContentType('text/html')

        ;

        $this->get('mailer')->send($message);
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
