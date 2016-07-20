<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
//sirve para extender de friendofsymfony
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

//sirve para validar los campos del formulario

/**
 * @ORM\Entity
 * @ORM\Table(name="usuario")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"usuario_trabajador" = "UsuarioTrabajador", "usuario_socio" = "UsuarioSocio"})
 * @Vich\Uploadable
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
     * @ORM\Column(name="api_key",type="string", unique=true,nullable=true)
     */
    protected $apiKey;

    /**
     * @ORM\ManyToMany(targetEntity="Usuario", mappedBy="misUsuariosRelacionados", cascade={"persist"})
     */
    private $usuarioRelacionado;

    /**
     * @ORM\ManyToMany(targetEntity="Usuario", inversedBy="usuarioRelacionado")
     * @ORM\JoinTable(name="usuario_relacionado",
     *      inverseJoinColumns={@ORM\JoinColumn(name="usuario_id", referencedColumnName="id")},
     *      joinColumns={@ORM\JoinColumn(name="usuario_pertenece_id", referencedColumnName="id")}
     *      )
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
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     * 
     * @Vich\UploadableField(mapping="user_image", fileNameProperty="userImage")
     * 
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $userImage;
    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();// construye los metodos y atributos de Base
        $this->usuarioRelacionado = new \Doctrine\Common\Collections\ArrayCollection();
        $this->misUsuariosRelacionados = new \Doctrine\Common\Collections\ArrayCollection();
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

    public function __toString()
    {
        return $this->nombre.' '.$this->apellidos;
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

     /* If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $image
     *
     * @return Product
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    /**
     * @return File
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $userImage
     *
     * @return Product
     */
    public function setUserImage($userImage)
    {
        $this->userImage = $userImage;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserImage()
    {
        return $this->userImage;
    }
}
