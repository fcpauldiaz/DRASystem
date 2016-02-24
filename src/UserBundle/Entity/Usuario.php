<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
//sirve para extender de friendofsymfony
use FOS\UserBundle\Model\User as BaseUser;

//sirve para validar los campos del formulario

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"usuario_trabajador" = "UsuarioTrabajador", "usuario_socio" = "UsuarioSocio"})
 *
 * @author  Pablo Díaz soporte@newtonlabs.com.gt
 */
abstract class Usuario extends BaseUser
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
     * @ORM\Column(type="string", unique=true,nullable=true)
     */
    protected $apiKey;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\RegistroHoras", mappedBy="usuarios")
     *
     * @var [type]
     */
    protected $registroHoras;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();// construye los metodos y atributos de Base
        $this->registroHoras = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set apiKey.
     *
     * @param string $apiKey
     *
     * @return Usuario
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Add registroHora.
     *
     * @param \AppBundle\Entity\RegistroHoras $registroHora
     *
     * @return Usuario
     */
    public function addRegistroHora(\AppBundle\Entity\RegistroHoras $registroHora)
    {
        $this->registroHoras[] = $registroHora;

        return $this;
    }

    /**
     * Remove registroHora.
     *
     * @param \AppBundle\Entity\RegistroHoras $registroHora
     */
    public function removeRegistroHora(\AppBundle\Entity\RegistroHoras $registroHora)
    {
        $this->registroHoras->removeElement($registroHora);
    }

    /**
     * Get registroHoras.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegistroHoras()
    {
        return $this->registroHoras;
    }

    public function __toString()
    {
        return $this->nombre.' '.$this->apellidos;
    }
}
