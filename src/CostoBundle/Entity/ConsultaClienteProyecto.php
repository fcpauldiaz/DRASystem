<?php

namespace CostoBundle\Entity;

use CostoBundle\Entity\Consulta as ConsultaMain;

/**
 * ConsutaPorActividad.
 * Clase para agrupar los cálculos necesarios
 * para realizar el query de horas por actividad.
 */
class ConsultaClienteProyecto extends ConsultaMain
{
    /**
     * Identificador  texto del area
     *
     * @var int
     */
    private $area;

    /**
     * Costo calculado por hora
     * @var double
     */
    private $costoPorHora;

    /**
     * Ideantificador del area
     * @var int
     */
    private $area_id;

    /**
     * Constructor inicial.
     *
     * @param Actividad $actividad
     * @param int       $horas
     * @param float     $costoTotal
     */
    public function __construct($area, $area_id, $horas, $horasPresupuesto, $costoTotal, $costoPresupuesto, $costo)
    {
        parent::__construct($horas, $horasPresupuesto, $costoTotal);
        $this->area = $area;
        $this->area_id = $area_id;

        $this->costoPresupuesto = $costoPresupuesto;
        $this->costoPorHora = $costo;
    }

    /**
     * Set presupuesto id.
     *
     * @param int $id
     */
    public function setPresupuestoId($id)
    {
        $this->presupuestoId = $id;

        return $this;
    }
    /**
     * Retorna el identificador del registro presupuesto.
     *
     * @return int
     */
    public function getPresupuestoId()
    {
        return $this->presupuestoId;
    }

    /**
     * Set area id.
     *
     * @param int $id
     */
    public function setArea($area)
    {
        $this->area = $area;

        return $this;
    }
    /**
     * Retorna el identificador del area
     *
     * @return int
     */
    public function getArea()
    {
        return $this->area;
    }

    public function getCostoPorHora()
    {
        return $this->costoPorHora;
    }

    public function getAreaId()
    {
      return $this->area_id;
    }


}
