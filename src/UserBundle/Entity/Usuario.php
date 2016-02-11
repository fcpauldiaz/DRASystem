<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
//sirve para extender de friendofsymfony
use FOS\UserBundle\Model\User as BaseUser;

//sirve para validar los campos del formulario

/**
 * @ORM\Table(name="usuario")
 * @ORM\Entity
 */
class Usuario extends BaseUser
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    protected $nombre;

    /**
     * @var string
     * @ORM\Column(name="apellidos", type="string", length=100)
     */
    protected $apellidos;

    /**
     * @var  string [<description>]
     * @ORM\Column(name="dpi",type="string",length=20, unique=true)
     */
    protected $dpi;

    /**
     * Número de identificación tributaria
     * @var  string 
     * @ORM\Column(name="nit", type="string",length=20,unique=true)
     */
    protected $nit;

    /**
     * @var int
     * @ORM\Column(name="telefono", type="integer",nullable=true)
     */
    protected $telefono;


    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();// construye los metodos y atributos de Base
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
     * Get expiresAt.
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Get credentials_expire_at.
     *
     * @return \DateTime
     */
    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }

    public function hasRole($role)
    {
        if (in_array($role, $this->getRoles())) {
            return true;
        }

        return false;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return Usuario
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellidos.
     *
     * @param string $apellidos
     *
     * @return Usuario
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos.
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set telefono.
     *
     * @param int $telefono
     *
     * @return Usuario
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono.
     *
     * @return int
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    public function __toString()
    {
        return $this->nombre.' '.$this->apellidos;
    }

    /**
     * Set dpi
     *
     * @param string $dpi
     *
     * @return Usuario
     */
    public function setDpi($dpi)
    {
        $this->dpi = $dpi;

        return $this;
    }

    /**
     * Get dpi
     *
     * @return string
     */
    public function getDpi()
    {
        return $this->dpi;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return Usuario
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }
}
