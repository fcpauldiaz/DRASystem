<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Cliente.
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity("nit")
 */
class Cliente
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
     * @var string
     *
     * @ORM\Column(name="nit", type="string", length=20, unique = true)
     */
    private $nit;

    /**
     * @var string
     *
     * @ORM\Column(name="razon_social", type="string", length=255)
     */
    private $razonSocial;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_comercial", type="string", length=255, nullable = true)
     */
    private $nombreComercial;

    /**
     * @var string
     *
     * @ORM\Column(name="servicios_prestados", type="string", length=255, nullable = true)
     */
    private $serviciosPrestados;

    /**
     * @var string
     *
     * @ORM\OneToMany(targetEntity="AsignacionCliente", mappedBy="cliente")
     */
    private $usuarioAsignados;

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
        $this->usuarioAsignados = new ArrayCollection();
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
     * Set nit.
     *
     * @param string $nit
     *
     * @return Cliente
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit.
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set razonSocial.
     *
     * @param string $razonSocial
     *
     * @return Cliente
     */
    public function setRazonSocial($razonSocial)
    {
        $this->razonSocial = $razonSocial;

        return $this;
    }

    /**
     * Get razonSocial.
     *
     * @return string
     */
    public function getRazonSocial()
    {
        return $this->razonSocial;
    }

    /**
     * Set nombreComercial.
     *
     * @param string $nombreComercial
     *
     * @return Cliente
     */
    public function setNombreComercial($nombreComercial)
    {
        $this->nombreComercial = $nombreComercial;

        return $this;
    }

    /**
     * Get nombreComercial.
     *
     * @return string
     */
    public function getNombreComercial()
    {
        return $this->nombreComercial;
    }

    /**
     * Set serviciosPrestados.
     *
     * @param string $serviciosPrestados
     *
     * @return Cliente
     */
    public function setServiciosPrestados($serviciosPrestados)
    {
        $this->serviciosPrestados = $serviciosPrestados;

        return $this;
    }

    /**
     * Get serviciosPrestados.
     *
     * @return string
     */
    public function getServiciosPrestados()
    {
        return $this->serviciosPrestados;
    }

    /**
     * Set fechaCreacion.
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Cliente
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
     * @return Cliente
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
     * @return Cliente
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
     * @return Cliente
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
     * Método para buscar en select2.
     *
     * @return string
     */
    public function showSearchParams()
    {
        return $this->nit.' : '.$this->razonSocial;
    }

    /**
     * Add usuarioAsignado.
     *
     * @param \AppBundle\Entity\AsignacionCliente $usuarioAsignado
     *
     * @return Cliente
     */
    public function addUsuarioAsignado(\AppBundle\Entity\AsignacionCliente $usuarioAsignado)
    {
        $this->usuarioAsignados[] = $usuarioAsignado;

        return $this;
    }

    /**
     * Remove usuarioAsignado.
     *
     * @param \AppBundle\Entity\AsignacionCliente $usuarioAsignado
     */
    public function removeUsuarioAsignado(\AppBundle\Entity\AsignacionCliente $usuarioAsignado)
    {
        $this->usuarioAsignados->removeElement($usuarioAsignado);
    }

    /**
     * Get usuarioAsignados.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarioAsignados()
    {
        return $this->usuarioAsignados;
    }

    public function clearUsuarios()
    {
        $this->usuarioAsignados->clear();
    }

    public function __toString()
    {
        if ($this->nombreComercial !== null && $this->serviciosPrestados !== null) {
            return $this->nombreComercial .': '. $this->serviciosPrestados;
        }

        return $this->razonSocial;
    }
}
