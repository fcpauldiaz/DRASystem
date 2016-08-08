<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Permiso.
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Permiso
{
    //se realizan dos constantes de permisos para poder
    //poder mostrarlos en dos columnas en la interfaz del usuarios
    const permisosActuales = [

        'Aprobar automáticamente las horas ingresadas' => 'ROLE_APROBACION_HORAS_AUTOMATICO',
        'Ver listado de horas ingresadas ' => 'ROLE_VER_LISTADO_GENERAL',
        'Ver listado de actividades' => 'ROLE_VER_ACTIVIDADES',
        'Ver listado de presupuestos' => 'ROLE_VER_PRESUPUESTOS',
        'Aprobar horas de otros usuarios' => 'ROLE_APROBACION_HORAS',
        'Ver listado de puestos y tipos' => 'ROLE_VER_PUESTO_Y_TIPO',
        'Ver listado de departamentos' => 'ROLE_VER_DEPARTAMENTO',
        'Ver listado de clientes' => 'ROLE_VER_CLIENTES',
        'Ver consultas de costos' => 'ROLE_VER_CONSULTAS',
        'Crear clientes' => 'ROLE_CREAR_CLIENTES',
        'Crear actividades' => 'ROLE_CREAR_ACTIVIDADES',
        'Ingresar horas invertidas' => 'ROLE_CREAR_HORAS',
        'Crear presupuestos' => 'ROLE_CREAR_PRESUPUESTOS',
        'Crear y editar usuarios' => 'ROLE_GESTIONAR_USUARIOS',
        'Crear puestos y tipos' => 'ROLE_CREAR_PUESTO_Y_TIPO',
        'Crear departamento' => 'ROLE_CREAR_DEPARTAMENTO',
        'Editar horas invertdas' => 'ROLE_EDITAR_HORAS',
        'Editar actividades' => 'ROLE_EDITAR_ACTIVIDADES',
        'Editar presupuestos' => 'ROLE_EDITAR_PRESUPUESTO',
        'Editar clientes' => 'ROLE_EDITAR_CLIENTES',
        'Permiso para que otros usuarios se asignen' => 'ROLE_ASIGNACION',
        'Editar puestos y tipos' => 'ROLE_EDITAR_PUESTO_Y_TIPO',
        'Editar departamento' => 'ROLE_EDITAR_DEPARTAMENTO',
        'Eliminar horas invertidas' => 'ROLE_ELIMINAR_HORAS',
        'Eliminar presupuestos' => 'ROLE_ELIMINAR_PRESUPUESTOS',
        'Eliminar actividades' => 'ROLE_ELIMINAR_ACTIVIDADES',
        'Eliminar clientes' => 'ROLE_ELIMINAR_CLIENTES',
        'Eliminar puestos y tipos' => 'ROLE_ELIMINAR_PUESTO_Y_TIPO',
        'Eliminar departamentos' => 'ROLE_ELIMINAR_DEPARTAMENTO',
        'Acceso total y panel de control' => 'ROLE_ADMIN',

    ];

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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set permiso.
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
     * Get permiso.
     *
     * @return permiso
     */
    public function getPermiso()
    {
        return $this->permiso;
    }

    /**
     * Set etiqueta.
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
     * Get etiqueta.
     *
     * @return string
     */
    public function getEtiqueta()
    {
        return $this->etiqueta;
    }

    /**
     * Add tipoPuesto.
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
     * Remove tipoPuesto.
     *
     * @param \UserBundle\Entity\TipoPuesto $tipoPuesto
     */
    public function removeTipoPuesto(\UserBundle\Entity\TipoPuesto $tipoPuesto)
    {
        $this->tipoPuestos->removeElement($tipoPuesto);
    }

    /**
     * Get tipoPuestos.
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
