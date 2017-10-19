<?php

namespace CostoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use CostoBundle\Form\Type\ConsultaHorasType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CostoBundle\Entity\ConsultaUsuario;

/**
 * ConsutlaHoras controller.
 *
 * @Security("is_granted('ROLE_VER_CONSULTAS')")
 * @Route("/consulta/horas")
 */
class ConsultaHorasController extends Controller
{
    /**
     * @ROUTE("", name="consulta_horas_usuario")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function consultaHorasAction(Request $request)
    {
        $form = $this->createForm(
            ConsultaHorasType::class
        );
        $form->handleRequest($request);
        if (!$form->isValid()) {
            return $this->render(
                'CostoBundle:ConsultaHoras:consultaHoras.html.twig',
                [
                    'verificador' => true, //mandar variable a javascript
                    'registros' => [],
                    'form' => $form->createView(),
                ]
            );
        }
        $data = $form->getData();
        $consulta = $data['consulta_filtro'];
        $registros = [];
        if ($consulta === 'Usuario') {
          $registros = $this->consultaUsuario($data);
        }
        if ($consulta === 'Cliente') {
          $registros = $this->consultaCliente($data);
        }
        if ($consulta === 'Presupuesto') {
          $registros = $this->consultaPresupuesto($data);
        }
        return $this->render(
            'CostoBundle:ConsultaHoras:consultaHoras.html.twig',
            [
                'verificador' => true, //mandar variable a javascript
                'registros' => $registros,
                'form' => $form->createView(),
            ]
        );
    }

    private function consultaUsuario($data)
    {
      $fechaInicio = $data['fechaInicio'];
      $fechaFinal = $data['fechaFinal'];
      $horas = $data['horas_extraordinarias'];
      $usuarios = $data['usuario'];
      $registros = [];
      foreach($usuarios as $usuario) {
        $registro = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:RegistroHoras')
            ->findByFechaAndUsuarioExtra($fechaInicio, $fechaFinal, $usuario, $horas);
        $registros = array_merge($registros, $registro);
      }
      return $registros;

    }

    private function consultaCliente($data)
    {
      $fechaInicio = $data['fechaInicio'];
      $fechaFinal = $data['fechaFinal'];
      $horas = $data['horas_extraordinarias'];
      $clientes = $data['cliente'];
      $registros = [];
      foreach($clientes as $cliente) {
        $registro = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('AppBundle:RegistroHoras')
          ->findByFechaAndClienteExtra($fechaInicio, $fechaFinal, $cliente, $horas);
        $registros = array_merge($registros, $registro);
      }
      return $registros;
    }

    private function consultaPresupuesto($data)
    {
      $fechaInicio = $data['fechaInicio'];
      $fechaFinal = $data['fechaFinal'];
      $horas = $data['horas_extraordinarias'];
      $presupuestos = $data['proyectoPresupuesto'];
      $registros = [];
      foreach($presupuestos as $presupuesto) {
        $registro = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('AppBundle:RegistroHoras')
          ->findByFechaAndClienteExtra($fechaInicio, $fechaFinal, $presupuesto, $horas);
        $registros = array_merge($registros, $registro);
      }
      return $registros;
    }

}
