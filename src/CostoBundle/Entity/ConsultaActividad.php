<?php

namespace CostoBundle\Entity;

use CostoBundle\Entity\Consulta as ConsultaMain;

/**
 * ConsutaPorActividad.
 * Clase para agrupar los cálculos necesarios
 * para realizar el query de horas por actividad.
 */
class ConsultaActividad extends ConsultaMain
{
    /**
     * Identificador el registro presupuesto.
     *
     * @var int
     */
    private $presupuestoId;

    /**
     * Constructor inicial.
     *
     * @param Actividad $actividad
     * @param int       $horas
     * @param float     $costoTotal
     */
    public function __construct($actividad, $horas, $horasPresupuesto, $costoTotal, $costoPresupuesto)
    {
        parent::__construct($horas, $horasPresupuesto, $costoTotal);
        $this->actividad = $actividad;

        $this->costoPresupuesto = $costoPresupuesto;
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
}
