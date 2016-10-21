<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * DatosPrestaciones.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class DatosPrestaciones
{

    const GASTO_FIJO = 1920;

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
     *            Bonificación Ley
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
     * @ORM\Column(name="otras_prestaciones", type="float", nullable=true)
     */
    private $otrasPrestaciones;

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
     * @ORM\Column(name="cuota_patronal",type="float", nullable=true)
     */
    private $cuotaPatronal;

    /**
     *  Gastos fijos de oficina 480
     *
     * @var float
     *
     * @ORM\Column(name="gastos_indirectos",type="float", nullable=true)
     */
    private $gastosIndirectos;

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
     * @Gedmo\Timestampable(on="create")
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="fecha_actualizacion", type="datetime")
     */
    private $fechaActualizacion;

    /**
     * @var string
     *
     * @Gedmo\Blameable(on="create")
      * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Codigo")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $creadoPor;

    /**
     * @var string
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\Codigo")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $actualizadoPor;

    public function __construct()
    {
        $this->descuentos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set cuotaPatronal.
     *
     * @param float $cuotaPatronal
     *
     * @return DatosPrestaciones
     */
    public function setCuotaPatronal($cuotaPatronal)
    {
        $this->cuotaPatronal = $cuotaPatronal;

        return $this;
    }

    /**
     * Get cuotaPatronal.
     *
     * @return float
     */
    public function getCuotaPatronal()
    {
        return $this->cuotaPatronal;
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
                 $this->getOtraBonificacion() +
                 $this->getGasolina() +
                 $this->getPrestacionesSobreSueldo() +
                 $this->getOtrasPrestaciones() +
                 $this->getViaticos() +
                 $this->getOtros()+
                 $this->getGastosIndirectos();
                //Esto ya está integrado en el costo
                //La indemnizacion, aguinaldo, bono14,cuota patronal
                //ya está integrado en el costo

        return $total;
    }

    public function getTotalDesglose()
    {
        $desglose = $this->getCuotaPatronal() +
                    $this->getBono14() +
                    $this->getAguinaldo() +
                    $this->getIndemnizacion() +
                    $this->getSueldo() * 0.0417;

        return $desglose;
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
        $prestacionesSobreSueldo = $sueldo * 0.4183;
        $calculoGeneral = $sueldo * 0.0833;//asi es el cálculo para el aguinaldo, indeminizacion y bono 14.
        $cuotaPatronal = $sueldo * 0.1267;
        $this->prestacionesSobreSueldo = $prestacionesSobreSueldo;
        $this->indemnizacion = $calculoGeneral;
        $this->aguinaldo = $calculoGeneral;
        $this->bono14 = $calculoGeneral;
        $this->cuotaPatronal = $cuotaPatronal;
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
        $this->fechaCreacion = $fecha;

        return $this;
    }

    /**
     * Get fecha.
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fechaCreacion;
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
     * Set fechaCreacion.
     *
     * @param \DateTime $fechaCreacion
     *
     * @return DatosPrestaciones
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion.
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaActualizacion.
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return DatosPrestaciones
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion.
     *
     * @return \DateTime
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Set creadoPor.
     *
     * @param string $creadoPor
     *
     * @return DatosPrestaciones
     */
    public function setCreadoPor($creadoPor)
    {
        $this->creadoPor = $creadoPor;

        return $this;
    }

    /**
     * Get creadoPor.
     *
     * @return string
     */
    public function getCreadoPor()
    {
        return $this->creadoPor;
    }

    /**
     * Set actualizadoPor.
     *
     * @param string $actualizadoPor
     *
     * @return DatosPrestaciones
     */
    public function setActualizadoPor($actualizadoPor)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }

    /**
     * Get actualizadoPor.
     *
     * @return string
     */
    public function getActualizadoPor()
    {
        return $this->actualizadoPor;
    }

    /**
     * Set gastosIndirectos
     *
     * @param float $gastosIndirectos
     *
     * @return DatosPrestaciones
     */
    public function setGastosIndirectos($gastosIndirectos)
    {
        $this->gastosIndirectos = $gastosIndirectos;

        return $this;
    }

    public function setGastosDRA()
    {
        $this->gastosIndirectos = self::GASTO_FIJO;
    }
    /**
     * Get gastosIndirectos
     *
     * @return float
     */
    public function getGastosIndirectos()
    {
        return $this->gastosIndirectos;
    }

    public function __toString()
    {
        return $this->sueldo.' '.$this->getUsuario();
    }
}
