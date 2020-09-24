<?php

namespace App\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class UserRegistrationCommandTest extends KernelTestCase
{
	public function testRunCommandWithValidOptions()
	{
		$kernel = static::createKernel();
		$application = new Application($kernel);

		$command = $application->find('app:user-registration');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'--fullname' => 'Wouter',
			'--email'    => 'wouter@mail.com',
		]);

		$this->assertStringContainsString('User created successfully', $commandTester->getDisplay());
	}

	public function testRunCommandWithFullNameOption()
	{
		$this->expectException(\RuntimeException::class);

		$kernel = static::createKernel();
		$application = new Application($kernel);

		$command = $application->find('app:user-registration');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['--fullname' => 'Wouter']);
	}

	public function testRunCommandWithEmptyFullNameOption()
	{
		$this->expectException(\RuntimeException::class);

		$kernel = static::createKernel();
		$application = new Application($kernel);

		$command = $application->find('app:user-registration');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['--fullname' => '']);
	}

	public function testRunCommandWithEmailOption()
	{
		$this->expectException(\RuntimeException::class);

		$kernel = static::createKernel();
		$application = new Application($kernel);

		$command = $application->find('app:user-registration');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['--email' => 'wouter@mail.com']);
	}

	public function testRunCommandWithEmptyEmailOption()
	{
		$this->expectException(\RuntimeException::class);

		$kernel = static::createKernel();
		$application = new Application($kernel);

		$command = $application->find('app:user-registration');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['--email' => '']);
	}

	public function testRunCommandWithInvalidEmailOption()
	{
		$this->expectException(\RuntimeException::class);

		$kernel = static::createKernel();
		$application = new Application($kernel);

		$command = $application->find('app:user-registration');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['--email' => 'wouter']);
	}

	public function testRunCommandWithExistedEmailOption()
	{
		$this->expectException(\RuntimeException::class);

		$kernel = static::createKernel();
		$application = new Application($kernel);

		$command = $application->find('app:user-registration');
		$commandTester = new CommandTester($command);
		$commandTester->execute(['--email' => 'user@mail.com']);
	}

	public function testRunCommandWithValidInputs()
	{
		$kernel = static::createKernel();
		$application = new Application($kernel);
		$command = $application->find('app:user-registration');

		$commandTester = new CommandTester($command);
		$commandTester->setInputs(['Wouter', 'wouter@mail.com']);
		$commandTester->execute([]);

		$this->assertStringContainsString('User created successfully', $commandTester->getDisplay());
	}

	public function testRunCommandWithEmptyFullNameInput()
	{
		$this->expectException(\RuntimeException::class);

		$kernel = static::createKernel();
		$application = new Application($kernel);
		$command = $application->find('app:user-registration');

		$commandTester = new CommandTester($command);
		$commandTester->setInputs(['']);
		$commandTester->execute([]);
	}

	public function testRunCommandWithEmptyEmailInput()
	{
		$this->expectException(\RuntimeException::class);

		$kernel = static::createKernel();
		$application = new Application($kernel);

		$command = $application->find('app:user-registration');
		$commandTester = new CommandTester($command);
		$commandTester->setInputs(['Wouter', '']);
		$commandTester->execute([]);
	}

	public function testRunCommandWithInvalidEmailInput()
	{
		$this->expectException(\RuntimeException::class);

		$kernel = static::createKernel();
		$application = new Application($kernel);

		$command = $application->find('app:user-registration');
		$commandTester = new CommandTester($command);
		$commandTester->setInputs(['Wouter', 'Wouter']);
		$commandTester->execute([]);
	}

	public function testRunCommandWithExistedEmailInput()
	{
		$this->expectException(\RuntimeException::class);

		$kernel = static::createKernel();
		$application = new Application($kernel);

		$command = $application->find('app:user-registration');
		$commandTester = new CommandTester($command);
		$commandTester->setInputs(['Wouter', 'user@mail.com']);
		$commandTester->execute([]);
	}
}
