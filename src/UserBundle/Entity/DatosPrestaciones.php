<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DatosPrestaciones
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class DatosPrestaciones
{
    /**
     * @var integer
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
     * @ORM\Column(name="bonificacionLey", type="float")
     */
    private $bonificacionLey;

    /**
     * @var float
     *
     * @ORM\Column(name="gasolina", type="float")
     */
    private $gasolina;

    /**
     * @var float
     *
     * @ORM\Column(name="prestacionesSobreSueldo", type="float")
     */
    private $prestacionesSobreSueldo;

    /**
     * @var float
     *
     * @ORM\Column(name="otrasPrestaciones", type="float")
     */
    private $otrasPrestaciones;

    /**
     * @var float
     *
     * @ORM\Column(name="otros", type="float")
     */
    private $otros;

    /**
     * @var float
     *
     * @ORM\Column(name="depreciacion", type="float")
     */
    private $depreciacion;


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
     * Set sueldo
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
     * Get sueldo
     *
     * @return float
     */
    public function getSueldo()
    {
        return $this->sueldo;
    }

    /**
     * Set bonificacionIncentivo
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
     * Get bonificacionIncentivo
     *
     * @return float
     */
    public function getBonificacionIncentivo()
    {
        return $this->bonificacionIncentivo;
    }

    /**
     * Set bonificacionLey
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
     * Get bonificacionLey
     *
     * @return float
     */
    public function getBonificacionLey()
    {
        return $this->bonificacionLey;
    }

    /**
     * Set gasolina
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
     * Get gasolina
     *
     * @return float
     */
    public function getGasolina()
    {
        return $this->gasolina;
    }

    /**
     * Set prestacionesSobreSueldo
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
     * Get prestacionesSobreSueldo
     *
     * @return float
     */
    public function getPrestacionesSobreSueldo()
    {
        return $this->prestacionesSobreSueldo;
    }

    /**
     * Set otrasPrestaciones
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
     * Get otrasPrestaciones
     *
     * @return float
     */
    public function getOtrasPrestaciones()
    {
        return $this->otrasPrestaciones;
    }

    /**
     * Set otros
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
     * Get otros
     *
     * @return float
     */
    public function getOtros()
    {
        return $this->otros;
    }

    /**
     * Set depreciacion
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
     * Get depreciacion
     *
     * @return float
     */
    public function getDepreciacion()
    {
        return $this->depreciacion;
    }

    public function __toString()
    {
        return $this->sueldo;
    }
}

