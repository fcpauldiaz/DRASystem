<?php

namespace CostoBundle\Entity;

/**
 * Consulta que padrea.
 * Clase para agrupar los cálculos necesarios
 * para realizar el query de horas.
 */
class Consulta
{
    /**
     * @var float
     *
     * total horas calculadas invertidaas
     */
    private $horasCalculadas;

    /**
     * @var float
     */
    private $horasExtraordinarias;
    /**
     * @var Actividad
     *
     * actividades
     */
    private $actividad;
    /**
     * @var float
     *
     * Horas presupuestadas que tenía en usuario
     */
    private $horasPresupuesto;

    /**
     * @var float
     *
     * diferencie entre costo y presupuesto
     */
    private $diferencia;

    /**
     * Se guarda el cálculo del costoMonetario.
     *
     * @var float
     */
    private $costoTotal;

    /**
     * Se guardar el costo del presupuesto.
     *
     * @var float
     */
    private $costoPresupuesto;

    /**
     * @var Usuario
     */
    private $usuario;

    public function __construct(
        $horas,
        $horasPresupuesto,
        $costoTotal
        ) {
        $this->horasCalculadas = $horas;
        $this->horasPresupuesto = $horasPresupuesto;
        $this->diferencia = 0;
        $this->costoTotal = $costoTotal;
    }

    /**
     * Set horas calculadas.
     *
     * @param float
     */
    public function setHorasCalculadas($horas)
    {
        $this->horasCalculadas = $horas;

        return $this;
    }

    /**
     * Get horas calculadas.
     *
     * @return float
     */
    public function getHorasCalculadas()
    {
        return $this->horasCalculadas;
    }

    /**
     * Set actividad.
     *
     * @param Actividad
     */
    public function setActividad($actividad)
    {
        $this->actividad = $actividad;

        return $this;
    }

    /**
     * Actividad.
     *
     * @return Actividad
     */
    public function getActividad()
    {
        return $this->actividad;
    }

    /**
     * Usuario.
     *
     * @param Usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }
    /**
     * Get Usuario.
     *
     * @return Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set horas presupuesto.
     *
     * @param float
     */
    public function setHorasPresupuesto($horasPresupuesto)
    {
        $this->horasPresupuesto = $horasPresupuesto;

        return $this;
    }

    /**
     * Get Horas Presupuesto.
     *
     * @return float
     */
    public function getHorasPresupuesto()
    {
        return $this->horasPresupuesto;
    }

    /**
     * Retrona la diferencia entre el costo y el presupuesto.
     *
     * @return float
     */
    public function getDiferencia()
    {
        return $this->diferencia;
    }

    /**
     * Método para calcular la diferencia entre el costo y el presupuesto.
     *
     * @return float
     */
    public function calcularDiferencia()
    {   
        $this->diferencia = $this->horasPresupuesto -
        $this->horasCalculadas;

        return $this;
    }
    /**
     * Retrona el costo total.
     *
     * @return float
     */
    public function getCostoTotal()
    {
        return $this->costoTotal;
    }

    /**
     * Set Costo total.
     *
     * @param float $costo
     */
    public function setCostoTotal($costo)
    {
        $this->costoTotal = $costo;

        return $this;
    }

    public function getCostoPresupuesto()
    {
        return $this->costoPresupuesto;
    }

    public function setCostoPresupuesto($costo)
    {
        $this->costoPresupuesto = $costo;

        return $this;
    }

    public function setHorasExtraordinarias($horas)
    {
        $this->horasExtraordinarias = $horas;
    }

    public function getHorasExtraordinarias()
    {
        return $this->horasExtraordinarias;
    }
}
