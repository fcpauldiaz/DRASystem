<?php

namespace UserBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use AppBundle\Entity\AsignacionCliente;

class AsignacionClientesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('app:client:ciro')//sintaxis del comando
            ->setDescription('Assign all clients to user Ciro.')//descripcion

        ;
    }
    /**
     * Método que ejecuta el comando.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return string con los nombres de los usuarios que han sido eliminados
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //se utiliza el manager de Symfony
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');
        //se utiliza el manager de FOS
        $um = $this->getContainer()->get('fos_user.user_manager');

        //buscar a un usuario
        $usuario = $em->getRepository('UserBundle:Usuario')->findOneById(19);

        $clientes = $em->getRepository('AppBundle:Cliente')->findAll();

        foreach ($clientes as $cliente) {
            $asignacion = new AsignacionCliente($usuario, $cliente);
            $em->persist($asignacion);
        }
        $em->flush();

        $output->writeln('Terminado');
    }
}
