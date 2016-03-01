<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * ContactoCliente.
 *
 * @ORM\Table()
 * @ORM\Entity
 * @UniqueEntity(fields={"nombreContacto", "apellidosContacto"},)
 */
class ContactoCliente
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
     * @ORM\Column(name="nombreContacto", type="string", length=255)
     */
    private $nombreContacto;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidosContacto", type="string", length=255)
     */
    private $apellidosContacto;

    /**
     * @var string
     *
     * @ORM\Column(name="telefonoContacto", type="array")
     */
    private $telefonoContacto;

    /**
     * @var string
     * 
     * @ORM\Column(name="correoContacto", type="array")
     */
    private $correoContacto;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Cliente", mappedBy="contactos")
     */
    private $clientes;

    public function __construct()
    {
        $this->clientes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombreContacto.
     *
     * @param string $nombreContacto
     *
     * @return ContactoCliente
     */
    public function setNombreContacto($nombreContacto)
    {
        $this->nombreContacto = $nombreContacto;

        return $this;
    }

    /**
     * Get nombreContacto.
     *
     * @return string
     */
    public function getNombreContacto()
    {
        return $this->nombreContacto;
    }

    /**
     * Set telefonoContacto.
     *
     * @param string $telefonoContacto
     *
     * @return ContactoCliente
     */
    public function setTelefonoContacto($telefonoContacto)
    {
        $this->telefonoContacto = $telefonoContacto;

        return $this;
    }

    /**
     * Get telefonoContacto.
     *
     * @return string
     */
    public function getTelefonoContacto()
    {
        return $this->telefonoContacto;
    }

    /**
     * Set correoContacto.
     *
     * @param string $correoContacto
     *
     * @return ContactoCliente
     */
    public function setCorreoContacto($correoContacto)
    {
        $this->correoContacto = $correoContacto;

        return $this;
    }

    /**
     * Get correoContacto.
     *
     * @return string
     */
    public function getCorreoContacto()
    {
        return $this->correoContacto;
    }

    /**
     * Add cliente.
     *
     * @param \AppBundle\Entity\Cliente $cliente
     *
     * @return ContactoCliente
     */
    public function addCliente(\AppBundle\Entity\Cliente $cliente)
    {
        $this->clientes[] = $cliente;

        return $this;
    }

    /**
     * Remove cliente.
     *
     * @param \AppBundle\Entity\Cliente $cliente
     */
    public function removeCliente(\AppBundle\Entity\Cliente $cliente)
    {
        $this->clientes->removeElement($cliente);
    }

    /**
     * Get clientes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClientes()
    {
        return $this->clientes;
    }

    public function __toString()
    {
        if (!empty($this->correoContacto)) {
            return $this->nombreContacto.' - '.$this->correoContacto[0];
        }

        return $this->nombreContacto;
    }

    /**
     * Set apellidosContacto.
     *
     * @param string $apellidosContacto
     *
     * @return ContactoCliente
     */
    public function setApellidosContacto($apellidosContacto)
    {
        $this->apellidosContacto = $apellidosContacto;

        return $this;
    }

    /**
     * Get apellidosContacto.
     *
     * @return string
     */
    public function getApellidosContacto()
    {
        return $this->apellidosContacto;
    }
}
