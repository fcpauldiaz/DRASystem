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
            ExcelType::class);

        $form->handleRequest($request);
        if ($form->isValid()) {
        	//save excel
        	$data = $form->getData();
	        $planilla = $data['excel'];
	        $hoja = $data['hoja'];
	        $hoja = $hoja - 1;//arreglar index.
	        $archivo = $planilla;
	        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($archivo);
	        $worksheet = $phpExcelObject->getSheet($hoja);
        	$this->validateClientExcel($worksheet);
        }

        return $this->render('AppBundle:Excel:newClientes.html.twig', 
        	[
        		'form' => $form->createView()
        	]);

		//subir archivo de clientes
   		//1. validar excel
   		
	}
   
   	private function validateClientExcel($worksheet)
   	{
   		//nit
   		//razon social
   		//tipo servicio
   		//gerente
   		//socio
   		$em = $this->getDoctrine()->getManager();
   		$count = 0;
   		foreach($worksheet->getRowIterator() as $row => $columns) {
   			$cellIterator = $columns->getCellIterator();
   			$columna = 0;
   			$cliente = new Cliente();
   			foreach($cellIterator as $cell) {
   				$data = $cell->getValue();
   				if ($count !== 0 && $data !== null) {
   					switch($columna) {
   						case 0: 
   							$cliente->setNit($data);
   							break;
   						case 1:
   							$cliente->setRazonSocial($data);
   							break;
   						case 2:
   							$cliente->setServiciosPrestados($data);
   							break;
              case 3;
                $cliente->setUsuarioAsignado($data);
                break;
   					}
   			
   				}
   				$columna++;
   				
   			}
   			$count++;
   			if ($cliente->getNit() !== null) {
   				$em->persist($cliente);
   			}

   		}
   		$em->flush();
   	}
    

}
