<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UserRegistrationCommand extends Command
{
	protected function configure()
	{
		$this
			->setDescription('Creates a new user.')
			->setHelp('This command allows you to create a new user')
			->addOption(
				'fullname',
				null,
				InputOption::VALUE_OPTIONAL,
				'The full name of the user.',
				null
			)
			->addOption(
				'email',
				null,
				InputOption::VALUE_OPTIONAL,
				'User email',
				null
			);
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		/*
		 * TODO: Implement this function to give administrator the possibility to addi new Users using command line
		 * Acceptance Criteria:
		 *      -> The command must be accessed through "bin/console ${command-name}".
		 *      -> The command must require the Administrator to enter the user’s Full Name & Email.
		 *      -> The Administrator can enter these details as command options.
		 *      -> In case the Administrator omits the command options, the command must ask for
		 *          these values interactively.
		 *      ->The command must validate that the Full Name is not blank and that the Email is a
		 *          well-formatted email. In case the validation fails, the command must re-prompt the user
		 *          to enter these values properly.
		 *      -> Once the user creation is complete, the command must output the resulting User Id
		 */

		return 0;
	}
}
