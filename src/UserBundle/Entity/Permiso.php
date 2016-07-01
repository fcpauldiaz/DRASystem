<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Permiso
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Permiso
{
        //se realizan dos constantes de permisos para poder
    //poder mostrarlos en dos columnas en la interfaz del usuarios
    const permisosActuales = [
    
        "Ver listado de horas ingresadas "=>"ROLE_VER_LISTADO_GENERAL",
        "Ver listado de actividades" => "ROLE_VER_ACTIVIDADES",
        "Ver listado de presupuestos" =>"ROLE_VER_PRESUPUESTOS",
        "Aprobación de horas" =>"ROLE_APROBACION_HORAS",
        "Ver listado de tipos de puesto" => "ROLE_VER_TIPO_PUESTO",
        "Ver listado de departamentos" => "ROLE_VER_DEPARTAMENTO",
        "Ver listado de clientes" => "ROLE_VER_CLIENTES",
        "Ver consultas de costos" => "ROLE_VER_CONSULTAS",
        "Crear clientes" => "ROLE_CREAR_CLIENTES",
        "Crear actividades" => "ROLE_CREAR_ACTIVIDADES",
        "Ingresar horas invertidas" => "ROLE_CREAR_HORAS",
        "Crear presupuestos" => "ROLE_CREAR_PRESUPUESTOS",
        "Crear usuarios" => "ROLE_CREAR_USUARIOS",
        "Crear tipo puesto" => "ROLE_CREAR_TIPO_PUESTO",
        "Crear departamento" => "ROLE_CREAR_DEPARTAMENTO", 
        "Editar horas invertdas" => "ROLE_EDITAR_HORAS",
        "Editar actividades" => "ROLE_EDITAR_ACTIVIDADES",
        "Editar presupuestos" => "ROLE_EDITAR_PRESUPUESTO",
        "Editar clientes" => "ROLE_EDITAR_CLIENTES",
        "Editar usuarios" => "ROLE_EDITAR_USUARIOS",
        "Editar tipo puesto" => "ROLE_EDITAR_TIPO_PUESTO",
        "Editar departamento" => "ROLE_EDITAR_DEPARTAMENTO",
        "Eliminar horas invertidas" => "ROLE_ELIMINAR_HORAS",
        "Eliminar presupuestos" => "ROLE_ELIMINAR_PRESUPUESTOS",
        "Eliminar actividades" => "ROLE_ELIMINAR_ACTIVIDADES",
        "Eliminar clientes" =>"ROLE_ELIMINAR_CLIENTES",
        "Eliminar tipo puesto" => "ROLE_ELIMINAR_TIPO_PUESTO",
        "Eliminar departamentos" => "ROLE_ELIMINAR_DEPARTAMENTO",
        "Acceso total y panel de control" =>"ROLE_ADMIN"
        
    ];
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="etiqueta", type="string", length=255)
     */
    private $etiqueta;

    /**
     * @var permiso
     *
     * @ORM\Column(name="permiso", type="string", length=255)
     */
    private $permiso;

    /**
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\TipoPuesto", mappedBy="permisos",  cascade={"remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $tipoPuestos;

    public function __construct($etiqueta, $permiso)
    {
        $this->etiqueta = $etiqueta;
        $this->permiso = $permiso;
        $this->tipoPuestos = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set permiso
     *
     * @param permiso $permiso
     *
     * @return Permiso
     */
    public function setPermiso($permiso)
    {
        $this->permiso = $permiso;

        return $this;
    }

    /**
     * Get permiso
     *
     * @return permiso
     */
    public function getPermiso()
    {
        return $this->permiso;
    }

    /**
     * Set etiqueta
     *
     * @param string $etiqueta
     *
     * @return Permiso
     */
    public function setEtiqueta($etiqueta)
    {
        $this->etiqueta = $etiqueta;

        return $this;
    }

    /**
     * Get etiqueta
     *
     * @return string
     */
    public function getEtiqueta()
    {
        return $this->etiqueta;
    }

    
    /**
     * Add tipoPuesto
     *
     * @param \UserBundle\Entity\TipoPuesto $tipoPuesto
     *
     * @return Permiso
     */
    public function addTipoPuesto(\UserBundle\Entity\TipoPuesto $tipoPuesto)
    {
        $this->tipoPuestos[] = $tipoPuesto;

        return $this;
    }

    /**
     * Remove tipoPuesto
     *
     * @param \UserBundle\Entity\TipoPuesto $tipoPuesto
     */
    public function removeTipoPuesto(\UserBundle\Entity\TipoPuesto $tipoPuesto)
    {
        $this->tipoPuestos->removeElement($tipoPuesto);
    }
    

    /**
     * Get tipoPuestos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTipoPuestos()
    {
        return $this->tipoPuestos;
    }
    
    public function __toString()
    {
        return $this->etiqueta;
    }
}
