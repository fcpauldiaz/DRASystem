<?php

namespace AppBundle\Tests\Controller;

use Symfony\Component\BrowserKit\Cookie;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactoClienteControllerTest extends WebTestCase
{
    /**
     * Este método prueba la interfaz de crear, editar y eliminar un contacto de cliente.
     *
     * @author  pablo diaz <fcpauldiaz@gmail.com>
     */
    public function testCreateContactoCliente()
    {
        // Create a new client to browse the application
        $client = static::createAuthorizedClient();

        // Create a new entry in the database
        $crawler = $client->request('GET', '/contactocliente/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Unexpected HTTP status code for GET /contactocliente/');

        $crawler = $client->click($crawler->selectLink('Crear Nuevo contacto')->link());

        // Fill in the form and submit it
        $form = $crawler->selectButton('Submit')->form(array(
            'appbundle_contactocliente[nombreContacto]' => 'Nombre',
            'appbundle_contactocliente[apellidosContacto]' => 'Apellido',
            'appbundle_contactocliente[puesto]' => 'Puesto',

            // ... other fields to fill
        ));

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        // Check data in the show view
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Nombre")')->count(), 'Missing element td:contains("Nombre")');
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Apellido")')->count(), 'Missing element td:contains("Apellido")');
        $this->assertGreaterThan(0, $crawler->filter('td:contains("Puesto")')->count(), 'Missing element td:contains("Puesto")');

        // Edit the entity
        $crawler = $client->click($crawler->selectLink('Editar')->link());

        $form = $crawler->selectButton('Update')->form(array(
            'appbundle_contactocliente[nombreContacto]' => 'NombreEditado',
            'appbundle_contactocliente[apellidosContacto]' => 'ApellidoEditado',
            'appbundle_contactocliente[puesto]' => 'PuestoEditado',
            // ... other fields to fill
        ));

        $client->submit($form);
        //
        $crawler = $client->followRedirect();

        // Check the element contains an attribute with value equals "Foo"
        $this->assertGreaterThan(0, $crawler->filter('[value="NombreEditado"]')->count(), 'Missing element [value="NombreEditado"]');
        $this->assertGreaterThan(0, $crawler->filter('[value="ApellidoEditado"]')->count(), 'Missing element [value="ApellidoEditado"]');
        $this->assertGreaterThan(0, $crawler->filter('[value="PuestoEditado"]')->count(), 'Missing element [value="PuestoEditado"]');

        // Delete the entity
        $client->submit($crawler->selectButton('Eliminar')->form());
        $crawler = $client->followRedirect();

        // Check the entity has been delete on the list
        $this->assertNotRegExp('/NombreEditado/', $client->getResponse()->getContent());
    }
    /**
     * Close doctrine connections to avoid having a 'too many connections'
     * message when running many tests
     * Sirve para eliminar la entidad de prueba creada.
     */
    public function tearDown()
    {
        //$this->getContainer()->get('doctrine')->getConnection()->close();
        //parent::tearDown();
    }

    /**
     * Este método sirve para autenticar el cliente y poder utilizar la aplicación como un usuario.
     *
     * @return Client
     */
    protected function createAuthorizedClient()
    {
        $client = static::createClient();
        $container = $client->getContainer();

        $session = $container->get('session');
        /** @var $userManager \FOS\UserBundle\Doctrine\UserManager */
        $userManager = $container->get('fos_user.user_manager');
        /** @var $loginManager \FOS\UserBundle\Security\LoginManager */
        $loginManager = $container->get('fos_user.security.login_manager');
        $firewallName = $container->getParameter('fos_user.firewall_name');

        $user = $userManager->findUserBy(array('username' => 'admin'));
        $loginManager->loginUser($firewallName, $user);

        // save the login token into the session and put it in a cookie
        $container->get('session')->set('_security_'.$firewallName,
            serialize($container->get('security.token_storage')->getToken()));
        $container->get('session')->save();
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }
}
