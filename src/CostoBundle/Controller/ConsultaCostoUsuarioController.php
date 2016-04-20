<?php

namespace CostoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CostoBundle\Form\Type\ConsultaCostoUsuarioType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CostoBundle\Entity\ConsultaUsuario;

/**
 * ConsutlaCosto controller.
 *
 * @Security("is_granted('ROLE_USER')")
 * @Route("/consulta/usuario/")
 */
class ConsultaCostoUsuarioController extends Controller
{
	 /**
     * @ROUTE("", name="consulta_usuario")
     *
     * @param Request $request [description]
     *
     * @return Response
     */
    public function consultaUsuarioAction(Request $request)
    {
        $form = $this->createForm(
            ConsultaCostoUsuarioType::class);
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
            $horas = $registro->getHorasInvertidas();
            $usuario = $registro->getIngresadoPor();
            $costo = $this->getDoctrine()
                ->getManager()
                ->getRepository('CostoBundle:Costo')
                ->findByFechaAndUsuario($fechaInicio, $fechaFinal, $usuario);
            $costoTotal = $horas * $costo['costo'];
            $actividad = $registro->getActividad();
            $horasPresupuesto = $this->calcularHorasPresupuesto($registrosPresupuesto, $actividad);
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
        $honorarios = $this->calcularHonorariosTotales($registros);

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

    private function calcularHorasPresupuesto($registrosPresupuesto, $actividad)
    {
        $horasPresupuesto = 0;
        foreach ($registrosPresupuesto as $presupuesto) {
            if ($presupuesto->getActividad() == $actividad) {
                $horasPresupuesto += $presupuesto->getHorasPresupuestadas();
            }
        }

        return $horasPresupuesto;
    }

    private function buscarRegistrosPresupuesto($registros, $usuario)
    {
        $returnArray = new \Doctrine\Common\Collections\ArrayCollection();
        foreach ($registros as $registro) {
            $proyecto = $registro->getProyectoPresupuesto();
            $registrosPresupuesto = $this->queryRegistroPresupuestos($proyecto, $usuario);
            $registrosArrayCollection = new \Doctrine\Common\Collections\ArrayCollection($registrosPresupuesto);
            $returnArray = $this
                 ->get('consulta.query_controller')
                ->mergeArrayCollectionAction($returnArray, $registrosArrayCollection);
        }

        return $returnArray->toArray();
    }

    private function calcularHonorariosTotales($registros)
    {
        $honorarios = 0;
        //se utilizará este array collection para no usar los honorarios
        //de un proyecto
        $proyectosAcum = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($registros as $registro) {
            $proyecto = $registro->getProyectoPresupuesto();
            //condición para no usar los honorarios de un mismo proyecto.
            if (!$proyectosAcum->contains($proyecto)) {
                $honorarios += $proyecto->getHonorarios();
            }
            //se agrega el proyecto ya analizado
            $proyectosAcum = $this
                ->get('consulta.query_controller')
                ->addArrayCollectionAction($proyectosAcum, $proyecto);
        }

        return $honorarios;
    }


       private function queryRegistroPresupuestos($proyecto, $usuario)
    {
        $repositoryRegistroHorasPresupuesto = $this->getDoctrine()->getRepository('AppBundle:RegistroHorasPresupuesto');
        $qb = $repositoryRegistroHorasPresupuesto->createQueryBuilder('registro');
        $qb
            ->select('registro')
            ->where('registro.proyecto = :proyecto')
            ->leftJoin('registro.usuariosAsignados', 'usuario')
            ->andWhere('usuario = :usuario')
            ->setParameter('proyecto', $proyecto)
            ->setParameter('usuario', $usuario)
            ;

        return $qb->getQuery()->getResult();
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