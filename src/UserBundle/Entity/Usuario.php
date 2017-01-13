<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
//sirve para extender de friendofsymfony
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"usuario_trabajador" = "UsuarioTrabajador", "usuario_socio" = "UsuarioSocio"})
 *
 * @author  Pablo Díaz soporte@newtonlabs.com.gt
 */
abstract class Usuario extends BaseUser implements \Hackzilla\Bundle\TicketBundle\Model\UserInterface
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
     * @ORM\Column(name="api_key",type="string", unique=true,nullable=true)
     */
    protected $apiKey;

    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\UsuarioRelacionado", mappedBy="usuarioPertenece")
     */
    private $usuarioRelacionado;

    /**
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\UsuarioRelacionado", mappedBy="usr")
     */
    private $misUsuariosRelacionados;

    /**
     * Código ya utilizado en DRA.
     *
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\Codigo")
     *
     * @var Entity
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $initials;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AsignacionCliente", mappedBy="usuario")
     */
    private $clientes;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct(); // construye los metodos y atributos de Base
        $this->usuarioRelacionado = new ArrayCollection();
        $this->misUsuariosRelacionados = new ArrayCollection();
        $this->clientes = new ArrayCollection();
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

    public function removeRole($role)
    {
        if ($role == 'ROLE_ASIGNACION') {
            return $this;
        }
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * Add usuarioRelacionado.
     *
     * @param \UserBundle\Entity\Usuario $usuarioRelacionado
     *
     * @return Usuario
     */
    public function addUsuarioRelacionado(\UserBundle\Entity\Usuario $usuarioRelacionado)
    {
        $usuarioRelacionado->addMisUsuariosRelacionado($this);
        $this->usuarioRelacionado[] = $usuarioRelacionado;

        return $this;
    }

    /**
     * Remove usuarioRelacionado.
     *
     * @param \UserBundle\Entity\Usuario $usuarioRelacionado
     */
    public function removeUsuarioRelacionado(\UserBundle\Entity\Usuario $usuarioRelacionado)
    {
        $usuarioRelacionado->remove($this);
        $this->usuarioRelacionado->removeElement($usuarioRelacionado);
    }

    /**
     * Get usuarioRelacionado.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuarioRelacionado()
    {
        return $this->usuarioRelacionado;
    }

    /**
     * Add misUsuariosRelacionado.
     *
     * @param \UserBundle\Entity\Usuario $misUsuariosRelacionado
     *
     * @return Usuario
     */
    public function addMisUsuariosRelacionado(\UserBundle\Entity\Usuario $misUsuariosRelacionado)
    {
        $this->misUsuariosRelacionados[] = $misUsuariosRelacionado;

        return $this;
    }

    /**
     * Remove misUsuariosRelacionado.
     *
     * @param \UserBundle\Entity\Usuario $misUsuariosRelacionado
     */
    public function removeMisUsuariosRelacionado(\UserBundle\Entity\Usuario $misUsuariosRelacionado)
    {
        $this->misUsuariosRelacionados->removeElement($misUsuariosRelacionado);
    }

    /**
     * Get misUsuariosRelacionados.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMisUsuariosRelacionados()
    {
        return $this->misUsuariosRelacionados;
    }

    /**
     * Set codigo.
     *
     * @param string $codigo
     *
     * @return UsuarioTrabajador
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo.
     *
     * @return string
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set initials.
     *
     * @param string $initials
     *
     * @return Usuario
     */
    public function setInitials($initials)
    {
        $this->initials = $initials;

        return $this;
    }

    /**
     * Get initials.
     *
     * @return string
     */
    public function getInitials()
    {
        return $this->initials;
    }

    public function __toString()
    {
        return $this->nombre.' '.$this->apellidos;
    }

    /**
     * Add cliente
     *
     * @param \AppBundle\Entity\AsignacionCliente $cliente
     *
     * @return Usuario
     */
    public function addCliente(\AppBundle\Entity\AsignacionCliente $cliente)
    {
        $this->clientes[] = $cliente;

        return $this;
    }

    /**
     * Remove cliente
     *
     * @param \AppBundle\Entity\AsignacionCliente $cliente
     */
    public function removeCliente(\AppBundle\Entity\AsignacionCliente $cliente)
    {
        $this->clientes->removeElement($cliente);
    }

    /**
     * Get clientes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClientes()
    {
        return $this->clientes;
    }
}
