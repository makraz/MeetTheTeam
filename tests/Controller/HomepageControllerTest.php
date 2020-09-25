<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomepageControllerTest extends WebTestCase
{
	public function testHomepage()
	{
		$client = static::createClient();

		$client->request('GET', '/');

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertSelectorTextContains('html h3.h3', 'Identify:');
	}

	public function testIdentifyUserWithInvalidEmail()
	{
		$client = static::createClient();
		$client->followRedirects();

		$crawler = $client->request('GET', '/');

		$form = $crawler->selectButton('Submit')->form();
		$form['username']->setValue('user');

		$client->submit($form);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertSelectorTextContains(
			'html div.alert-danger',
			'This value is not a valid email address.',
			$client->getResponse()->getContent()
		);
	}

	public function testIdentifyUserWithNotExistEmail()
	{
		$client = static::createClient();
		$client->followRedirects();

		$crawler = $client->request('GET', '/');

		$form = $crawler->selectButton('Submit')->form();
		$form['username']->setValue('not.existing.user@mail.com');

		$client->submit($form);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertSelectorTextContains(
			'html div.alert-danger',
			'This value is not exist',
			$client->getResponse()->getContent()
		);
	}

	public function testIdentifyUserWithExistAndValidEmail()
	{
		$client = static::createClient();
		$client->followRedirects();

		$crawler = $client->request('GET', '/');

		$form = $crawler->selectButton('Submit')->form();
		$form['username']->setValue('user@mail.com');

		$client->submit($form);

		$this->assertEquals(200, $client->getResponse()->getStatusCode());
		$this->assertSelectorTextContains(
			'html h3.h3',
			'Authenticate:',
			$client->getResponse()->getContent()
		);
	}
}
