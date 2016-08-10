<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\Column(name="nit", type="string", length=20)
     */
    private $nit;

    /**
     * @var string
     *
     * @ORM\Column(name="numero_contrato", type="string", length=100, nullable = true)
     */
    private $numeroContrato;

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
     * @ORM\Column(name="nombre_corto", type="string", length=255, nullable = true)
     */
    private $nombreCorto;

    /**
     * @var string
     *
     * @ORM\Column(name="servicios_prestados", type="string", length=255, nullable = true)
     */
    private $serviciosPrestados;

    /**
     * @var string
     *
     * @ORM\Column(name="codigo_sat", type="string", length=255, nullable = true)
     */
    private $codigoSAT;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\ContactoCliente", inversedBy="clientes")
     * @ORM\JoinTable(name="cliente_contactos")
     */
    private $contactos;

    public function __construct()
    {
        $this->contactos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set numeroContrato.
     *
     * @param string $numeroContrato
     *
     * @return Cliente
     */
    public function setNumeroContrato($numeroContrato)
    {
        $this->numeroContrato = $numeroContrato;

        return $this;
    }

    /**
     * Get numeroContrato.
     *
     * @return string
     */
    public function getNumeroContrato()
    {
        return $this->numeroContrato;
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
     * Set nombreCorto.
     *
     * @param string $nombreCorto
     *
     * @return Cliente
     */
    public function setNombreCorto($nombreCorto)
    {
        $this->nombreCorto = $nombreCorto;

        return $this;
    }

    /**
     * Get nombreCorto.
     *
     * @return string
     */
    public function getNombreCorto()
    {
        return $this->nombreCorto;
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
     * Set codigoSAT.
     *
     * @param string $codigoSAT
     *
     * @return Cliente
     */
    public function setCodigoSAT($codigoSAT)
    {
        $this->codigoSAT = $codigoSAT;

        return $this;
    }

    /**
     * Get codigoSAT.
     *
     * @return string
     */
    public function getCodigoSAT()
    {
        return $this->codigoSAT;
    }

    /**
     * Add contacto.
     *
     * @param \AppBundle\Entity\ContactoCliente $contacto
     *
     * @return Cliente
     */
    public function addContacto(\AppBundle\Entity\ContactoCliente $contacto)
    {
        $this->contactos[] = $contacto;

        return $this;
    }

    /**
     * Remove contacto.
     *
     * @param \AppBundle\Entity\ContactoCliente $contacto
     */
    public function removeContacto(\AppBundle\Entity\ContactoCliente $contacto)
    {
        $this->contactos->removeElement($contacto);
    }

    /**
     * Get contactos.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContactos()
    {
        return $this->contactos;
    }

    public function __toString()
    {
        if ($this->nombreComercial != null) {
            return $this->nombreComercial;
        }

        return $this->razonSocial;
    }
}
