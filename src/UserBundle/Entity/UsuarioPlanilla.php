<?php

namespace UserBundle\Entity;


/**
 * UsuarioPlanilla
 *
 */
class UsuarioPlanilla
{
   
    /**
     * @var string
     *
     */
    private $codigo;

    /**
     * @var string
     *
     */
    private $departamento;

    /**
     * @var string
     *
     */
    private $apellido;

    /**
     * @var string
     *
     */
    private $nombre;

    /**
     * @var string
     *
     */
    private $base;

    /**
     * @var string
     *
     */
    private $dias;

    /**
     * @var string
     *
     */
    private $bonificacion;

    /**
     * @var string
     *
     */
    private $otraBonificacion;

    /**
     * @var string
     *
     */
    private $depreciacion;

    /**
     * @var string
     *
     */
    private $gasolina;

    /**
     * @var string
     *
     */
    private $ingresos;

    /**
     * @var string
     *
     */
    private $igss;

    /**
     * @var string
     *
     */
    private $aguinaldo;

    /**
     * @var string
     *
     */
    private $corporacion;

    /**
     * @var string
     *
     */
    private $comcel;

    /**
     * @var string
     *
     */
    private $isr;

    /**
     * @var string
     *
     */
    private $otrosDescuentos;

    /**
     * @var string
     *
     */
    private $prestaciones;

    /**
     * @var string
     *
     */
    private $valens1;

    /**
     * @var string
     *
     */
    private $valens2;

    /**
     * @var string
     *
     */
    private $valens3;

    /**
     * @var string
     *
     */
    private $liquido;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     *
     * @return UsuarioPlanilla
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set departamento
     *
     * @param string $departamento
     *
     * @return UsuarioPlanilla
     */
    public function setDepartamento($departamento)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento
     *
     * @return string
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     *
     * @return UsuarioPlanilla
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return UsuarioPlanilla
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set base
     *
     * @param string $base
     *
     * @return UsuarioPlanilla
     */
    public function setBase($base)
    {
        $this->base = $base;

        return $this;
    }

    /**
     * Get base
     *
     * @return string
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * Set dias
     *
     * @param string $dias
     *
     * @return UsuarioPlanilla
     */
    public function setDias($dias)
    {
        $this->dias = $dias;

        return $this;
    }

    /**
     * Get dias
     *
     * @return string
     */
    public function getDias()
    {
        return $this->dias;
    }

    /**
     * Set bonificacion
     *
     * @param string $bonificacion
     *
     * @return UsuarioPlanilla
     */
    public function setBonificacion($bonificacion)
    {
        $this->bonificacion = $bonificacion;

        return $this;
    }

    /**
     * Get bonificacion
     *
     * @return string
     */
    public function getBonificacion()
    {
        return $this->bonificacion;
    }

    /**
     * Set otraBonificacion
     *
     * @param string $otraBonificacion
     *
     * @return UsuarioPlanilla
     */
    public function setOtraBonificacion($otraBonificacion)
    {
        $this->otraBonificacion = $otraBonificacion;

        return $this;
    }

    /**
     * Get otraBonificacion
     *
     * @return string
     */
    public function getOtraBonificacion()
    {
        return $this->otraBonificacion;
    }

    /**
     * Set depreciacion
     *
     * @param string $depreciacion
     *
     * @return UsuarioPlanilla
     */
    public function setDepreciacion($depreciacion)
    {
        $this->depreciacion = $depreciacion;

        return $this;
    }

    /**
     * Get depreciacion
     *
     * @return string
     */
    public function getDepreciacion()
    {
        return $this->depreciacion;
    }

    /**
     * Set gasolina
     *
     * @param string $gasolina
     *
     * @return UsuarioPlanilla
     */
    public function setGasolina($gasolina)
    {
        $this->gasolina = $gasolina;

        return $this;
    }

    /**
     * Get gasolina
     *
     * @return string
     */
    public function getGasolina()
    {
        return $this->gasolina;
    }

    /**
     * Set ingresos
     *
     * @param string $ingresos
     *
     * @return UsuarioPlanilla
     */
    public function setIngresos($ingresos)
    {
        $this->ingresos = $ingresos;

        return $this;
    }

    /**
     * Get ingresos
     *
     * @return string
     */
    public function getIngresos()
    {
        return $this->ingresos;
    }

    /**
     * Set igss
     *
     * @param string $igss
     *
     * @return UsuarioPlanilla
     */
    public function setIgss($igss)
    {
        $this->igss = $igss;

        return $this;
    }

    /**
     * Get igss
     *
     * @return string
     */
    public function getIgss()
    {
        return $this->igss;
    }

    /**
     * Set aguinaldo
     *
     * @param string $aguinaldo
     *
     * @return UsuarioPlanilla
     */
    public function setAguinaldo($aguinaldo)
    {
        $this->aguinaldo = $aguinaldo;

        return $this;
    }

    /**
     * Get aguinaldo
     *
     * @return string
     */
    public function getAguinaldo()
    {
        return $this->aguinaldo;
    }

    /**
     * Set corporacion
     *
     * @param string $corporacion
     *
     * @return UsuarioPlanilla
     */
    public function setCorporacion($corporacion)
    {
        $this->corporacion = $corporacion;

        return $this;
    }

    /**
     * Get corporacion
     *
     * @return string
     */
    public function getCorporacion()
    {
        return $this->corporacion;
    }

    /**
     * Set comcel
     *
     * @param string $comcel
     *
     * @return UsuarioPlanilla
     */
    public function setComcel($comcel)
    {
        $this->comcel = $comcel;

        return $this;
    }

    /**
     * Get comcel
     *
     * @return string
     */
    public function getComcel()
    {
        return $this->comcel;
    }

    /**
     * Set isr
     *
     * @param string $isr
     *
     * @return UsuarioPlanilla
     */
    public function setIsr($isr)
    {
        $this->isr = $isr;

        return $this;
    }

    /**
     * Get isr
     *
     * @return string
     */
    public function getIsr()
    {
        return $this->isr;
    }

    /**
     * Set otrosDescuentos
     *
     * @param string $otrosDescuentos
     *
     * @return UsuarioPlanilla
     */
    public function setOtrosDescuentos($otrosDescuentos)
    {
        $this->otrosDescuentos = $otrosDescuentos;

        return $this;
    }

    /**
     * Get otrosDescuentos
     *
     * @return string
     */
    public function getOtrosDescuentos()
    {
        return $this->otrosDescuentos;
    }

    /**
     * Set prestaciones
     *
     * @param string $prestaciones
     *
     * @return UsuarioPlanilla
     */
    public function setPrestaciones($prestaciones)
    {
        $this->prestaciones = $prestaciones;

        return $this;
    }

    /**
     * Get prestaciones
     *
     * @return string
     */
    public function getPrestaciones()
    {
        return $this->prestaciones;
    }

    /**
     * Set valens
     *
     * @param string $valens
     *
     * @return UsuarioPlanilla
     */
    public function setValens1($valens)
    {
        $this->valens1 = $valens;

        return $this;
    }

    /**
     * Get valens
     *
     * @return string
     */
    public function getValens1()
    {
        return $this->valens1;
    }

    /**
     * Set valens2
     *
     * @param string $valens2
     *
     * @return UsuarioPlanilla
     */
    public function setValens2($valens2)
    {
        $this->valens2 = $valens2;

        return $this;
    }

    /**
     * Get valens2
     *
     * @return string
     */
    public function getValens2()
    {
        return $this->valens2;
    }

    /**
     * Set valens3
     *
     * @param string $valens3
     *
     * @return UsuarioPlanilla
     */
    public function setValens3($valens3)
    {
        $this->valens3 = $valens3;

        return $this;
    }

    /**
     * Get valens3
     *
     * @return string
     */
    public function getValens3()
    {
        return $this->valens3;
    }

    /**
     * Set liquido
     *
     * @param string $liquido
     *
     * @return UsuarioPlanilla
     */
    public function setLiquido($liquido)
    {
        $this->liquido = $liquido;

        return $this;
    }

    /**
     * Get liquido
     *
     * @return string
     */
    public function getLiquido()
    {
        return $this->liquido;
    }
}

