<?php

namespace UserBundle\Command;

use AppBundle\Entity\AsignacionCliente;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AsignacionClientesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:client:ciro')//sintaxis del comando
            ->setDescription('Assign all clients to user Ciro.')//descripcion
            ->addArgument('idUser', InputArgument::REQUIRED, 'The id of the user.')

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

        //buscar a un usuario
        $usuario = $em->getRepository('UserBundle:Usuario')->findOneById($input->getArgument('idUser'));

        $clientes = $em->getRepository('AppBundle:Cliente')->findAll();

        foreach ($clientes as $cliente) {
            $asignacion = new AsignacionCliente($usuario, $cliente);
            $em->persist($asignacion);
        }
        $em->flush();

        $output->writeln('Terminado');
    }
}
