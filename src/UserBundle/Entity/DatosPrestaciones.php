<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatosPrestaciones.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class DatosPrestaciones
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="sueldo", type="float")
     */
    private $sueldo;

    /**
     * @var float
     *
     * @ORM\Column(name="bonificacionIncentivo", type="float")
     */
    private $bonificacionIncentivo;

    /**
     * @var float
     *
     * @ORM\Column(name="bonificacionLey", type="float", nullable=true)
     */
    private $bonificacionLey;

    /**
     * @var float
     *
     * @ORM\Column(name="gasolina", type="float",nullable=true)
     */
    private $gasolina;

    /**
     * @var float
     *
     * @ORM\Column(name="prestacionesSobreSueldo", type="float",nullable=true)
     */
    private $prestacionesSobreSueldo;

    /**
     * @var float
     *
     * @ORM\Column(name="otrasPrestaciones", type="float", nullable=true)
     */
    private $otrasPrestaciones;

    /**
     * @var float
     *
     * @ORM\Column(name="otros", type="float", nullable=true)
     */
    private $otros;

    /**
     * @var float
     *
     * @ORM\Column(name="depreciacion", type="float",nullable=true)
     */
    private $depreciacion;

    /**
     * 8.33%.
     *
     * @var float
     *
     * @ORM\Column(name="indemnizacion",type="float", nullable=true)
     */
    private $indemnizacion;

    /**
     * 8.33%.
     *
     * @var float
     *
     * @ORM\Column(name="aguinaldo",type="float", nullable=true)
     */
    private $aguinaldo;

    /**
     *  8.33%.
     *
     * @var float
     *
     * @ORM\Column(name="bono14",type="float", nullable=true)
     */
    private $bono14;

    /**
     *  Cuota Patronal 10.67% +1 % +1% = 12.67%.
     *
     * @var float
     *
     * @ORM\Column(name="igss",type="float", nullable=true)
     */
    private $igss;

    /**
     * @var Usuario
     * @ORM\ManyToOne(targetEntity="UsuarioTrabajador",inversedBy = "datosPrestaciones")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $usuario;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set sueldo.
     *
     * @param float $sueldo
     *
     * @return DatosPrestaciones
     */
    public function setSueldo($sueldo)
    {
        $this->sueldo = $sueldo;

        return $this;
    }

    /**
     * Get sueldo.
     *
     * @return float
     */
    public function getSueldo()
    {
        return $this->sueldo;
    }

    /**
     * Set bonificacionIncentivo.
     *
     * @param float $bonificacionIncentivo
     *
     * @return DatosPrestaciones
     */
    public function setBonificacionIncentivo($bonificacionIncentivo)
    {
        $this->bonificacionIncentivo = $bonificacionIncentivo;

        return $this;
    }

    /**
     * Get bonificacionIncentivo.
     *
     * @return float
     */
    public function getBonificacionIncentivo()
    {
        return $this->bonificacionIncentivo;
    }

    /**
     * Set bonificacionLey.
     *
     * @param float $bonificacionLey
     *
     * @return DatosPrestaciones
     */
    public function setBonificacionLey($bonificacionLey)
    {
        $this->bonificacionLey = $bonificacionLey;

        return $this;
    }

    /**
     * Get bonificacionLey.
     *
     * @return float
     */
    public function getBonificacionLey()
    {
        return $this->bonificacionLey;
    }

    /**
     * Set gasolina.
     *
     * @param float $gasolina
     *
     * @return DatosPrestaciones
     */
    public function setGasolina($gasolina)
    {
        $this->gasolina = $gasolina;

        return $this;
    }

    /**
     * Get gasolina.
     *
     * @return float
     */
    public function getGasolina()
    {
        return $this->gasolina;
    }

    /**
     * Set prestacionesSobreSueldo.
     *
     * @param float $prestacionesSobreSueldo
     *
     * @return DatosPrestaciones
     */
    public function setPrestacionesSobreSueldo($prestacionesSobreSueldo)
    {
        $this->prestacionesSobreSueldo = $prestacionesSobreSueldo;

        return $this;
    }

    /**
     * Get prestacionesSobreSueldo.
     *
     * @return float
     */
    public function getPrestacionesSobreSueldo()
    {
        return $this->prestacionesSobreSueldo;
    }

    /**
     * Set otrasPrestaciones.
     *
     * @param float $otrasPrestaciones
     *
     * @return DatosPrestaciones
     */
    public function setOtrasPrestaciones($otrasPrestaciones)
    {
        $this->otrasPrestaciones = $otrasPrestaciones;

        return $this;
    }

    /**
     * Get otrasPrestaciones.
     *
     * @return float
     */
    public function getOtrasPrestaciones()
    {
        return $this->otrasPrestaciones;
    }

    /**
     * Set otros.
     *
     * @param float $otros
     *
     * @return DatosPrestaciones
     */
    public function setOtros($otros)
    {
        $this->otros = $otros;

        return $this;
    }

    /**
     * Get otros.
     *
     * @return float
     */
    public function getOtros()
    {
        return $this->otros;
    }

    /**
     * Set depreciacion.
     *
     * @param float $depreciacion
     *
     * @return DatosPrestaciones
     */
    public function setDepreciacion($depreciacion)
    {
        $this->depreciacion = $depreciacion;

        return $this;
    }

    /**
     * Get depreciacion.
     *
     * @return float
     */
    public function getDepreciacion()
    {
        return $this->depreciacion;
    }

    /**
     * Set usuario.
     *
     * @param \UserBundle\Entity\UsuarioTrabajador $usuario
     *
     * @return DatosPrestaciones
     */
    public function setUsuario(\UserBundle\Entity\UsuarioTrabajador $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario.
     *
     * @return \UserBundle\Entity\UsuarioTrabajador
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set indemnizacion.
     *
     * @param float $indemnizacion
     *
     * @return DatosPrestaciones
     */
    public function setIndemnizacion($indemnizacion)
    {
        $this->indemnizacion = $indemnizacion;

        return $this;
    }

    /**
     * Get indemnizacion.
     *
     * @return float
     */
    public function getIndemnizacion()
    {
        return $this->indemnizacion;
    }

    /**
     * Set aguinaldo.
     *
     * @param float $aguinaldo
     *
     * @return DatosPrestaciones
     */
    public function setAguinaldo($aguinaldo)
    {
        $this->aguinaldo = $aguinaldo;

        return $this;
    }

    /**
     * Get aguinaldo.
     *
     * @return float
     */
    public function getAguinaldo()
    {
        return $this->aguinaldo;
    }

    /**
     * Set bono14.
     *
     * @param float $bono14
     *
     * @return DatosPrestaciones
     */
    public function setBono14($bono14)
    {
        $this->bono14 = $bono14;

        return $this;
    }

    /**
     * Get igss.
     *
     * @return float
     */
    public function getIgss()
    {
        return $this->igss;
    }

    /**
     * Set igss.
     *
     * @param float $igss
     *
     * @return DatosPrestaciones
     */
    public function setIgss($igss)
    {
        $this->igss = $igss;

        return $this;
    }

    /**
     * Get bono14.
     *
     * @return float
     */
    public function getBono14()
    {
        return $this->bono14;
    }

    public function __toString()
    {
        return $this->sueldo.' '.$this->getUsuario();
    }
}
