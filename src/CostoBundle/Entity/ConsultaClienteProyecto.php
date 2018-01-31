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
     * Identificador el registro presupuesto.
     *
     * @var int
     */
    private $area;

    private $costoPorHora;

    /**
     * Constructor inicial.
     *
     * @param Actividad $actividad
     * @param int       $horas
     * @param float     $costoTotal
     */
    public function __construct($area, $horas, $horasPresupuesto, $costoTotal, $costoPresupuesto, $costo)
    {
        parent::__construct($horas, $horasPresupuesto, $costoTotal);
        $this->area = $area;

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


}
