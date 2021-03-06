<?php

namespace AppBundle\Entity;

/**
 * AsignacionClienteRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClienteRepository extends \Doctrine\ORM\EntityRepository
{
  /**
     * @return ClienteRepository
     */
    public function findByUsuariosAsignados($clientId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('asignacion')
            ->from('AppBundle:Cliente', 'cliente')
            ->innerJoin('AppBundle:AsignacionCliente', 'asignacion', 'with', 'asignacion.cliente = cliente.id')
            ->where('cliente.id = :clientId')
            ->setParameter('clientId', $clientId);
        ;
        return $qb->getQuery()->getResult();
    }

}
