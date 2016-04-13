<?php

namespace CostoBundle\Entity;

/**
 * ConsutaPorActividad.
 * Clase para agrupar los cálculos necesarios
 * para realizar el query de horas por actividad.
 */
class ConsultaActividad
{
    /**
     * @var float
     *
     * total horas calculadas invertidaas
     */
    private $horasCalculadas;

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
     * Identificador el registro presupuesto.
     *
     * @var int
     */
    private $presupuestoId;

    /**
     * Cliente asignado.
     *
     * @var Cliente
     */
    private $cliente;

    /**
     * @var Usuario
     */
    private $usuario;

    /**
     * Constructor inicial.
     *
     * @param Actividad $actividad
     * @param int       $horas
     * @param float     $costoTotal
     */
    public function __construct($actividad, $horas, $horasPresupuesto, $costoTotal)
    {
        $this->actividad = $actividad;
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
     * @return float.
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
     * Set Cliente.
     *
     * @param Cliente $cliente
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente.
     *
     * @return Cliente.
     */
    public function getCliente()
    {
        return $this->cliente;
    }
}
