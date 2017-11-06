<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


class DashBoardController extends Controller
{
    /**
     * @Route("/forbidden")
     */
    public function forbiddenAction()
    {
        return $this->render('default/error403.html.twig');
    }

    /**
     * @Route("/.well-known/acme-challenge/nZi0CwAnzY1q6flK--ADvFQ8xZ9FGChMG45mcTYRcq8", name="cert")
    */
    public function indexAction(Request $request)
    {
      $file = 'key.txt';
      $response = new BinaryFileResponse($file);
      $response->headers->set('Content-Type', 'text/plain');
      return $response;

    }
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $cantidadUsuarios = $this->queryUsuarios();
        $cantidadHoras = $this->queryHorasIngresadas();
        $cantidadHorasPresupuestadas = $this->queryHorasPresupuesto();
        $cantidadCostos = $this->queryCosto();
        $user = $this->getUser();
        $this->changeUserApiKey();
        $apiKey = $user->getApiKey();
        $password = $user->getPassword();

        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
            'cantidadUsuarios' => $cantidadUsuarios,
            'cantidadHoras' => $cantidadHoras !== null ? $cantidadHoras : 0,
            'cantidadHorasPresupuestadas' => $cantidadHorasPresupuestadas !== null ? $cantidadHorasPresupuestadas : 0,
            'cantidadCostos' => $cantidadCostos,
            'apiKey' => $apiKey,
            'password' => $password,
        ));
    }
    /**
     * Every time the user goes to the main page will change api key.
     */
    private function changeUserApiKey()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $user->setApiKey(uniqid().uniqid());
        $em->persist($user);
        $em->flush();
    }

    /**
     * Query que devuelve la cantidad de costos guardados del usuairo actual.
     *
     * @return
     */
    private function queryCosto()
    {
        $em = $this->getDoctrine()->getManager();
        $usuarioActual = $this->getUser();
        $repositoryCosto = $em->getRepository('CostoBundle:Costo');
        $queryCosto = $repositoryCosto->createQueryBuilder('costo')
            ->select('COUNT(costo.id)')
            ->where('costo.usuario = :usuarioActual')
            ->setParameter('usuarioActual', $usuarioActual);

        return $queryCosto->getQuery()->getSingleScalarResult();
    }

    /**
     * Query que devuelve las horas del presupuesto.
     *
     * @return float or null
     */
    private function queryHorasPresupuesto()
    {
        $em = $this->getDoctrine()->getManager();
        $usuarioActual = $this->getUser();
        $repositoryPresupuesto = $em->getRepository('AppBundle:RegistroHorasPresupuesto');
        $queryPresupuestos = $repositoryPresupuesto->createQueryBuilder('presupuesto')
            ->select('SUM(presupuesto.horasPresupuestadas)')
            ->innerJoin('presupuesto.usuario', 'usuario')
            ->where('usuario.id = :user')
            ->setParameter('user', $usuarioActual->getId());

        return $queryPresupuestos->getQuery()->getSingleScalarResult();
    }

    /**
     * Query que devuelve la cantidad de hoas ingresadas por el usuario logueado.
     *
     * @return float or null
     */
    private function queryHorasIngresadas()
    {
        $em = $this->getDoctrine()->getManager();
        $usuarioActual = $this->getUser();
        $repositoryHoras = $em->getRepository('AppBundle:RegistroHoras');
        $queryHoras = $repositoryHoras->createQueryBuilder('horas')
            ->select('SUM(horas.horasInvertidas)')
            ->innerJoin('horas.ingresadoPor', 'autor')
            ->where('autor = :usuario')
            ->setParameter('usuario', $usuarioActual);

        return $queryHoras->getQuery()->getSingleScalarResult();
    }

    /**
     * Query que devuelve la cantidad de usuarios actuales en el sistema.
     *
     * @return int or NULL
     */
    private function queryUsuarios()
    {
        $em = $this->getDoctrine()->getManager();
        $repositoryUsuarios = $em->getRepository('UserBundle:Usuario');
        $queryUsuarios = $repositoryUsuarios->createQueryBuilder('usuario')
            ->select('COUNT(usuario.id)');

        return $queryUsuarios->getQuery()->getSingleScalarResult();
    }
}
