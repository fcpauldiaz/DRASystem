<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * CostoRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActividadRepository extends EntityRepository
{
    public function findByRegistros($registros)
    {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->select('a')
            ->innerJoin('AppBundle:RegistroHoras', 'r', 'with', 'r.actividad = a.id')
            ->where('r IN (:registros)')
            ->setParameter('registros', $registros);

        return $qb->getQuery()->getResult();
    }

    public function findByRegistrosPresupuesto($registros)
    {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->select('a')
            ->innerJoin('AppBundle:RegistroHorasPresupuesto', 'r', 'with', 'r.actividad = a.id')
            ->where('r IN (:registros)')
            ->setParameter('registros', $registros);

        return $qb->getQuery()->getResult();
    }
}