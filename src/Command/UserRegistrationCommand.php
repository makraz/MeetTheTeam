<?php

namespace App\Command;

use App\Entity\User;
use App\Validator\Constraints\UniqueValueInEntity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserRegistrationCommand extends Command
{
    /**
     * @var ValidatorInterface $validator
     */
    private ValidatorInterface $validator;

    /**
     * @var EntityManagerInterface $entityManager
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var string $defaultName
     */
    protected static $defaultName = 'app:user-registration';

    /**
     * UserRegistrationCommand constructor.
     *
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

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

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fullName = $input->getOption('fullname');
        $email = $input->getOption('email');

        if (!is_null($fullName)) {
            $this->validateFullName($fullName);
        }

        if (!is_null($email)) {
            $this->validateEmail($email);
        }

        $output->writeln([
            '=================',
            'User Registration',
            '=================',
        ]);

        $helper = $this->getHelper('question');

        if (is_null($fullName)) {
            $question = new ConfirmationQuestion('Full Name: ', false);
            $question->setNormalizer(function ($value) {
                // $value can be null here
                return $value ? trim($value) : '';
            });
            $question->setValidator(function ($answer) {
                $this->validateFullName($answer);

                return $answer;
            });

            $fullName = $helper->ask($input, $output, $question);
        }

        if (is_null($email)) {
            $question = new ConfirmationQuestion('Email: ', false);
            $question->setNormalizer(function ($value) {
                // $value can be null here
                return $value ? trim($value) : '';
            });
            $question->setValidator(function ($answer) {
                $this->validateEmail($answer);

                return $answer;
            });

            $email = $helper->ask($input, $output, $question);
        }

        $user = new User();
        $user->setFullName($fullName);
        $user->setEmail($email);

        $violations = $this->validator->validate($user);

        if (count($violations) > 0) {
            throw new \RuntimeException($violations[0]->getMessage());
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln("User ID: {$user->getId()}");
        $output->writeln([
            '============================',
            '√ User created successfully.',
            '============================',
        ]);

        return 0;
    }

    /**
     * @param string $fullName
     *
     * @return void
     */
    private function validateFullName(string $fullName): void
    {
        $violations = $this->validator->validate($fullName, [
            new NotBlank(),
        ]);

        if (!is_string($fullName) || count($violations) !== 0) {
            throw new \RuntimeException($violations[0]->getMessage());
        }
    }

    /**
     * @param string $email
     *
     * @return void
     */
    private function validateEmail(string $email): void
    {
        $violations = $this->validator->validate($email, [
            new NotBlank(),
            new Email(),
            new UniqueValueInEntity([
                'field'       => 'email',
                'entityClass' => User::class,
            ]),
        ]);

        if (!is_string($email) || count($violations) !== 0) {
            throw new \RuntimeException($violations[0]->getMessage());
        }
    }
}
