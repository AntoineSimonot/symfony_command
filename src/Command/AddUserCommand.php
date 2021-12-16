<?php


namespace App\Command;

use App\Entity\User;
use App\Helper\UserHelper;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\TextUI\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AddUserCommand extends \Symfony\Component\Console\Command\Command
{
    protected static $defaultName = 'app:add-user';

    private UserHelper $userHelper;
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserHelper $userHelper, UserPasswordHasherInterface $hasher)
    {
        // 3. Update the value of the private entityManager variable through injection
        $this->userHelper = $userHelper;
        $this->hasher = $hasher;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'The email of the user.')
            ->addArgument('password', InputArgument::REQUIRED, 'The password of the user.');
    }

    // 4. Use the entity manager in the command code ...
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->userHelper->CreateUser($input->getArgument('email'), $input->getArgument('password'),$this->hasher);
        $output->writeln('User '.$input->getArgument('email').' created');
        return 0;
    }
}