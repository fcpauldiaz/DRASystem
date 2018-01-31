<?php

namespace CostoBundle\Controller;

use CostoBundle\Entity\ConsultaUsuario;
use CostoBundle\Form\Type\ConsultaCostoUsuarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * ConsutlaCosto controller.
 *
 * @Security("is_granted('ROLE_VER_CONSULTAS')")
 * @Route("/consulta/usuario/")
 */
class ConsultaCostoUsuarioController extends Controller
{
    /**
     * @ROUTE("", name="consulta_usuario")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function consultaUsuarioAction(Request $request)
    {
        $form = $this->createForm(
            ConsultaCostoUsuarioType::class
        );
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'CostoBundle:ConsultaUsuario:consultaUsuario.html.twig',
                [
                     'verificador' => true, //mandar variable a javascript
                    'consultaUsuario' => [],
                    'form' => $form->createView(),
                ]
            );
        }
        $data = $form->getData();

        $fechaInicio = $data['fechaInicio'];
        $fechaFinal = $data['fechaFinal'];
        $usuario = $data['usuario'];

        $registros = $this->queryRegistroHorasPorUsuario($fechaInicio, $fechaFinal, $usuario);
        $registrosPresupuesto = $this->buscarRegistrosPresupuesto($registros, $usuario);
        $returnArray = [];
        foreach ($registros as $registro) {
            $cliente = $registro->getCliente();
            $horas = $registro->getHorasAprobadas($data['horas_extraordinarias']);
            $usuario = $registro->getIngresadoPor();
            $costo = $this->getDoctrine()
                ->getManager()
                ->getRepository('CostoBundle:Costo')
                ->findByFechaAndUsuario($fechaInicio, $fechaFinal, $usuario);
            $costoTotal = $horas * $costo['costo'];
            $actividad = $registro->getActividad();

            $horasPresupuesto = $this
                ->get('consulta.query_controller')
                ->calcularHorasPresupuestoAction($registrosPresupuesto, $actividad);

            $costoPresupuesto = $horasPresupuesto * $costo['costo'];
            if ($actividad->getActividadNoCargable() === true) {
                $costoTotal = 0;
            }

            $consultaUsuario = new ConsultaUsuario(
                $usuario,
                $horas,
                $horasPresupuesto,
                0,
                $costoTotal
                );
            $consultaUsuario->setCostoPresupuesto($costoPresupuesto);
            $consultaUsuario->setCliente($cliente);
            $consultaUsuario->setActividad($actividad);
            $consultaUsuario->calcularDiferencia();
            $returnArray[] = $consultaUsuario;
        }
        $honorarios = $this
            ->get('consulta.query_controller')
            ->calcularHonorariosTotalesAction($registros);

        return $this->render(
            'CostoBundle:ConsultaUsuario:consultaUsuario.html.twig',
            [
                'honorarios' => $honorarios,
                'verificador' => false,  //mandar variable a javascript
                'consultaUsuario' => $returnArray,
                'form' => $form->createView(),
            ]
        );
    }

    private function buscarRegistrosPresupuesto($registros, $usuario)
    {
        $returnArray = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($registros as $registro) {
            $proyecto = $registro->getProyectoPresupuesto();
            $registrosPresupuesto = $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('AppBundle:RegistroHorasPresupuesto')
                ->findByUsuario($proyecto, $usuario);
            $registrosArrayCollection = new \Doctrine\Common\Collections\ArrayCollection($registrosPresupuesto);
            $returnArray = $this
                 ->get('consulta.query_controller')
                ->mergeArrayCollectionAction($returnArray, $registrosArrayCollection);
        }

        return $returnArray->toArray();
    }

    private function queryRegistroHorasPorUsuario($fechaInicio, $fechaFinal, $usuario)
    {
        $repositoryRegistroHoras = $this->getDoctrine()->getRepository('AppBundle:RegistroHoras');
        $qb = $repositoryRegistroHoras->createQueryBuilder('registro');
        $qb
            ->select('registro')
            ->where('registro.fechaHoras >= :fechaInicio')
            ->andWhere('registro.fechaHoras <= :fechaFinal')
            ->andWhere('registro.ingresadoPor = :usuario')
            ->setParameter('fechaInicio', $fechaInicio)
            ->setParameter('fechaFinal', $fechaFinal)
            ->setParameter('usuario', $usuario)
            ;

        return $qb->getQuery()->getResult();
    }
}
