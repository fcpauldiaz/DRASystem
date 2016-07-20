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
     * @ORM\Column(name="sueldo_base", type="float")
     */
    private $sueldo;

    /**
     * @var float
     *
     * @ORM\Column(name="bonificacion_incentivo", type="float")
     */
    private $bonificacionIncentivo;

    /**
     * @var float
     *
     * @ORM\Column(name="otra_bonificacion", type="float", nullable=true)
     */
    private $otraBonificacion;

    /**
     * @var float
     *
     * @ORM\Column(name="gasolina", type="float",nullable=true)
     */
    private $gasolina;

    /**
     * @var float
     *
     * @ORM\Column(name="prestaciones_sobre_sueldo", type="float",nullable=true)
     */
    private $prestacionesSobreSueldo;

    /**
     * @var float
     *
     * @ORM\Column(name="cargos_indirectos", type="float", nullable=true)
     */
    private $cargosIndirectos;

    /**
     * @var float
     *
     * @ORM\Column(name="viaticos", type="float", nullable=true)
     */
    private $viaticos;

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
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\Descuento", mappedBy="prestaciones")
     * 
     * @var [type]
     */
    private $descuentos;

    /**
     * Fecha de creacion.
     *
     * @ORM\Column(name="fecha_creacion", type="datetime")
     */
    private $fecha;

    public function __construct()
    {
        $this->descuentos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fecha = new \DateTime();
    }

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
    /**
     * Calcular la suma de todas las prestaciones.
     *
     * @return float
     */
    public function calcularTotalPrestaciones()
    {
        $total = $this->getSueldo() +
                 $this->getBonificacionIncentivo() +
                 $this->getBonificacionLey() +
                 $this->getGasolina() +
                 $this->getPrestacionesSobreSueldo() +
                 $this->getCargosIndirectos() +
                 $this->getViaticos() +
                 $this->getOtros() +
                 $this->getIndemnizacion() +
                 $this->getAguinaldo() +
                 $this->getBono14() +
                 $this->getIgss();

        return $total;
    }

    /**
     * Set cargosIndirectos.
     *
     * @param float $cargosIndirectos
     *
     * @return DatosPrestaciones
     */
    public function setCargosIndirectos($cargosIndirectos)
    {
        $this->cargosIndirectos = $cargosIndirectos;

        return $this;
    }

    /**
     * Get cargosIndirectos.
     *
     * @return float
     */
    public function getCargosIndirectos()
    {
        return $this->cargosIndirectos;
    }

    /**
     * Set viaticos.
     *
     * @param float $viaticos
     *
     * @return DatosPrestaciones
     */
    public function setViaticos($viaticos)
    {
        $this->viaticos = $viaticos;

        return $this;
    }

    /**
     * Get viaticos.
     *
     * @return float
     */
    public function getViaticos()
    {
        return $this->viaticos;
    }

    public function __toString()
    {
        return $this->sueldo.' '.$this->getUsuario();
    }

    /**
     * Set otraBonificacion.
     *
     * @param float $otraBonificacion
     *
     * @return DatosPrestaciones
     */
    public function setOtraBonificacion($otraBonificacion)
    {
        $this->otraBonificacion = $otraBonificacion;

        return $this;
    }

    /**
     * Get otraBonificacion.
     *
     * @return float
     */
    public function getOtraBonificacion()
    {
        return $this->otraBonificacion;
    }

    public function calcularPrestaciones()
    {
        $sueldo = $this->sueldo;
        $prestacionesSobreSueldo = $sueldo * 0.42;
        $calculoGeneral = $sueldo * 0.0833;//asi es el cálculo para el aguinaldo, indeminizacion y bono 14.
        $cuotaPatronal = $sueldo * 0.1267;
        $this->prestacionesSobreSueldo = $prestacionesSobreSueldo;
        $this->indemnizacion = $calculoGeneral;
        $this->aguinaldo = $calculoGeneral;
        $this->bono14 = $calculoGeneral;
        $this->igss = $cuotaPatronal;
    }

    /**
     * Add descuento.
     *
     * @param \UserBundle\Entity\Descuento $descuento
     *
     * @return DatosPrestaciones
     */
    public function addDescuento(\UserBundle\Entity\Descuento $descuento)
    {
        $this->descuentos[] = $descuento;

        return $this;
    }

    /**
     * Remove descuento.
     *
     * @param \UserBundle\Entity\Descuento $descuento
     */
    public function removeDescuento(\UserBundle\Entity\Descuento $descuento)
    {
        $this->descuentos->removeElement($descuento);
    }

    /**
     * Get descuentos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDescuentos()
    {
        return $this->descuentos;
    }

    /**
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return DatosPrestaciones
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha.
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }
}
