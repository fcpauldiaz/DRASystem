<?php

namespace CostoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class QueryController extends Controller
{
    /**
     * Agregar elemento a un array collection.
     *p.
     *
     * @param ArrayCollection $array1
     * @param T               $item
     */
    public function addArrayCollectionAction($array1, $item)
    {
        if (!$array1->contains($item)) {
            $array1->add($item);
        }

        return $array1;
    }

    /**
     * Unir dos ArrayCollection.
     *
     * @param ArrayCollection $array1
     * @param ArrayCollection $array2
     *
     * @return ArrayCollection
     */
    public function mergeArrayCollectionAction($array1, $array2)
    {
        foreach ($array2 as $item) {
            if (!$array1->contains($item)) {
                $array1->add($item);
            }
        }

        return $array1;
    }

    public function calcularHonorariosTotalesAction($registros)
    {
        $honorarios = 0;
        //se utilizará este array collection para no usar los honorarios
        //de un proyecto
        $proyectosAcum = new \Doctrine\Common\Collections\ArrayCollection();

        foreach ($registros as $registro) {
            $proyecto = $registro->getProyectoPresupuesto();
            //condición para no usar los honorarios de un mismo proyecto.
            if (!$proyectosAcum->contains($proyecto) && !is_null($proyecto)) {
                $honorarios += $proyecto->getHonorarios();
            }
            //se agrega el proyecto ya analizado
            $proyectosAcum = $this
                ->addArrayCollectionAction($proyectosAcum, $proyecto);
        }

        return $honorarios;
    }

    public function calcularHonrariosPorCliente($cliente)
    {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('SUM(DISTINCT(p.honorarios))')
            ->from('AppBundle:RegistroHorasPresupuesto', 'r')
            ->innerJoin('AppBundle:Area', 'area', 'with', 'area.id = r.area')
            ->innerJoin('AppBundle:ProyectoPresupuesto', 'p', 'with', 'p.id = r.proyecto')
            ->where('r.cliente = :cliente_id')
            ->setParameter('cliente_id', $cliente);
        return $qb->getQuery()->getOneOrNullResult();
    }

    public function calcularHorasPresupuestoAction($registrosPresupuesto, $actividad)
    {
        $horasPresupuesto = 0;
        foreach ($registrosPresupuesto as $presupuesto) {
            if (method_exists($actividad, 'getArea')){
                $actividades = $this->getActividadesPorArea($actividad->getArea()->getId());

                foreach($actividades as $innerActividad) {
                    if ($innerActividad == $actividad) {
                    $horasPresupuesto += $presupuesto->getHorasPresupuestadas();
                    }
                }
            }
        }

        return $horasPresupuesto;
    }

    public function calcularHorasPorAreaAction($presupuestos, $area)
    {
        $horasPresupuesto = 0;
        foreach($presupuestos as $presupuesto) {
            $horasPresupuesto += $presupuesto->getHorasPresupuestadas();
        }
        return $horasPresupuesto;
    }

    private function getActividadesPorArea($idArea) {
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder('a');
        $qb
            ->select('a')
            ->from('AppBundle:Actividad', 'a')
            ->innerJoin('AppBundle:Area', 'r', 'with', 'r.id = a.area')
            ->where('r.id = (:id_area)')
            ->setParameter('id_area', $idArea);

        return $qb->getQuery()->getResult();
    }

    /**
     * Método para buscar los usuarios relacionados.
     *
     * @param UserBundle:Usuario $usuario
     *#
     * @return array de UserBundle:Usuario
     */
    public function buscarUsuariosPorSocioAction($usuario)
    {
        $sql = '
            SELECT u1.id
            FROM usuario u1
            WHERE u1.id in
            (
                SELECT r1.usuario_pertenece_id
                FROM usuario u
                JOIN usuario_relacionado r1 ON r1.usuario_id =  u.id
                WHERE u.id = ?
            )
            ';

        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->bindValue(1, $usuario->getId());
        $stmt->execute();
        $res = $stmt->fetchAll();
        $ids = [];
        foreach ($res as $innerRes) {
            $ids[] = $innerRes['id'];
        }

        return $this
            ->getDoctrine()
            ->getRepository('UserBundle:Usuario')
            ->findById($ids)
            ;
    }
}
