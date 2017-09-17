<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;
use AppBundle\Form\Type\ExcelType;
use AppBundle\Entity\Cliente;
use AppBundle\Entity\Actividad;
use AppBundle\Entity\Area;
use UserBundle\Entity\Departamento;
use AppBundle\Entity\AsignacionCliente;

/**
 * Upload controller.
 *
 * @Security("is_granted('ROLE_USER')")
 */
class UploadController extends Controller
{
    /**
     * @Route("/excel/clientes", name ="excel_clientes")
     * Método para subir excel de clientes
     *
     * @param Request $request
     *
     * @return Response
     */
    public function uploadClientAction(Request $request)
    {
        $usuario = $this->getUser();
        if (!is_object($usuario) || !$usuario instanceof UserInterface) {
            throw new AccessDeniedException('El usuario no tiene acceso.');
        }

        $form = $this->createForm(
        ExcelType::class
        );

        $form->handleRequest($request);
        if ($form->isValid()) {
            //save excel
            $data = $form->getData();
            $planilla = $data['excel'];
            $hoja = $data['hoja'];
            $hoja = $hoja - 1; //arreglar index.
            $archivo = $planilla;
            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($archivo);
            $worksheet = $phpExcelObject->getSheet($hoja);
            $this->validateClientExcel($worksheet);
        }

        return $this->render(
            'AppBundle:Excel:newClientes.html.twig',
        [
            'form' => $form->createView(),
        ]
        );
    }
    /**
     * @Route("/excel/actividades", name ="excel_actividades")
     * Método para subir excel de actividades
     *
     * @param Request $request
     *
     * @return Response
     */
    public function uploadActivitiesAction(Request $request)
    {
        $form = $this->createForm(
        ExcelType::class
      );

        $form->handleRequest($request);
        if ($form->isValid()) {
            //save excel
            $data = $form->getData();
            $planilla = $data['excel'];
            $hoja = $data['hoja'];
            $hoja = $hoja - 1; //arreglar index.
            $archivo = $planilla;
            $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($archivo);
            $worksheet = $phpExcelObject->getSheet($hoja);
            $this->validateActividadExcel($worksheet);
        }

        return $this->render(
          'AppBundle:Excel:newActividadExcel.html.twig',
    [
      'form' => $form->createView(),
    ]
      );
    }

    private function validateActividadExcel($worksheet)
    {
        $em = $this->getDoctrine()->getManager();
        $count = 0;
        foreach ($worksheet->getRowIterator() as $row => $columns) {
            $cellIterator = $columns->getCellIterator();
            $columna = 0;
            $actividad = new Actividad();
            foreach ($cellIterator as $cell) {
                $data = $cell->getValue();
                if ($count !== 0 && $data !== null) {
                    switch ($columna) {
            case 0:
               $dep = $worksheet->getCell('E'.$count)->getValue();
              $departamento = $em->getRepository('UserBundle:Departamento')->findOneBy(['nombreDepartamento' => $dep]);
              if ($departamento === null) {
                  $departamento = new Departamento($dep);
                  $em->persist($departamento);
                  $em->flush();
              }
              $area = $em
                ->getRepository('AppBundle:Area')
                ->findOneBy(['nombre' => $data, 'departamento' => $departamento]);
              if ($area === null) {
                  $area = new Area($data);
                  $area->setDepartamento($departamento);
                  $em->persist($area);
                  $em->flush();
              }

              $actividad->setArea($area);
              break;
            case 1:
              $actividad->setNombre($data);
              break;
            case 2:
              if ($data !== null) {
                  $actividad->setActividadNoCargable(false);
              }
              break;
            case 3:
              if ($data !== null) {
                  $actividad->setActividadNoCargable(true);
              }
              break;
          }
                }
                ++$columna;
            }
            ++$count;
            if ($actividad->getArea() !== null && $actividad->getNombre() !== null) {
                $em->persist($actividad);
            }
        }

        $em->flush();
    }

    private function validateClientExcel($worksheet)
    {
        //nit
        //razon social
        //tipo servicio
        //gerente
        $em = $this->getDoctrine()->getManager();
        $count = 0;
        foreach ($worksheet->getRowIterator() as $row => $columns) {
            $cellIterator = $columns->getCellIterator();
            $columna = 0;
            $cliente = new Cliente();
            foreach ($cellIterator as $cell) {
                $data = $cell->getValue();
                if ($count !== 0 && $data !== null) {
                    switch ($columna) {
                        case 0:
                            $cliente->setNit($data);
                            break;
                        case 1:
                            $cliente->setRazonSocial($data);
                            break;
                        case 2:
                            $cliente->setServiciosPrestados($data);
                            break;
            case 3:
              $usuario = $em->getRepository('UserBundle:Usuario')->findOneById($data);

              $asignacion = new AsignacionCliente($usuario, $cliente);
              $em->persist($asignacion);
              $cliente->addUsuarioAsignado($asignacion);
              break;
                    }
                }
                ++$columna;
            }
            ++$count;
            if ($cliente->getNit() !== null) {
                $em->persist($cliente);
            }
        }
        $em->flush();
    }
}
