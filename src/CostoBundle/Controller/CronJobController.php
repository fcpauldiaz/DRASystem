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
        $em = $this->getDoctrine()->getManager();

        $usuarios = $em->getRepository('UserBundle:UsuarioTrabajador')->findAll();

        $firstDay = new \DateTime('first day of this month');

        $lastDay = new \Datetime('last day of this month');

        foreach ($usuarios as $usuario) {
            $entidadCosto = new Costo();
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
            $em->flush();
        }

        return $this->redirect($this->generateUrl('cron_job_view'));
    }

    /**
     * @Route("costo/calcular/view", name = "cron_job_view")
     */
    public function showButtonAction()
    {
        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('cron_job'))
            ->add('submit', 'submit', array('label' => 'Calcular'))
            ->getForm()
        ;

        return $this->render('CostoBundle:Costo:cronJob.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
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
                ->andWhere('registro.fechaHoras < :fechaFin')
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
