<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProyectoPresupuestoRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProyectoPresupuestoRepository extends EntityRepository
{
    /**
     * @return ProyectoPresupuesto
     */
    public function findAllFetch()
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('proyecto', 'clientes', 'presupuestoIndividual', 'creadoPor', 'actualizadoPor')
            ->from('AppBundle:ProyectoPresupuesto', 'proyecto')
            ->leftJoin('proyecto.clientes', 'clientes')
            ->leftJoin('proyecto.presupuestoIndividual', 'presupuestoIndividual')
            ->leftJoin('proyecto.creadoPor', 'creadoPor')
            ->leftJoin('proyecto.actualizadoPor', 'actualizadoPor')
        ;
        return $qb->getQuery()->getResult();
    }

}