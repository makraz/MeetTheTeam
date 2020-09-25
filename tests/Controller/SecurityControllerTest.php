<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
	public function testLoginPageWithIdentifyUser()
	{
		$client = static::createClient();

		$container = static::$kernel->getContainer();
		$session = $container->get('session');
		$session->set('identify_user_email', 'user@mail.com');

		$client->request('GET', '/login');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertSelectorTextContains('html h3.h3', 'Authenticate:');

	}

	public function testLoginPageWithAnonymousUser()
	{
		$client = static::createClient();
		$client->request('GET', '/login');

		$this->assertEquals(403, $client->getResponse()->getStatusCode());
	}
}
