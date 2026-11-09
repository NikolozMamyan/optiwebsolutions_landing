<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsCommand(name: 'app:admin:create', description: 'Créer un administrateur avec un mot de passe saisi confidentiellement.')]
final class CreateAdminCommand extends Command
{
    public function __construct(private EntityManagerInterface $manager, private UserRepository $users, private UserPasswordHasherInterface $hasher, private ValidatorInterface $validator)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'Adresse e-mail de connexion');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = mb_strtolower(trim($input->getArgument('email')));
        if (count($this->validator->validate($email, [new Assert\NotBlank(), new Assert\Email(), new Assert\Length(max: 180)]))) {
            $io->error('Adresse e-mail invalide.');
            return Command::INVALID;
        }
        if ($this->users->findOneBy(['email' => $email])) {
            $io->error('Un compte existe déjà avec cette adresse.');
            return Command::FAILURE;
        }
        if (!$input->isInteractive()) {
            $io->error('Utilisez le mode interactif pour saisir le mot de passe confidentiellement.');
            return Command::INVALID;
        }
        $password = $io->askHidden('Mot de passe (12 caractères minimum)', static function (?string $password): string {
            if (mb_strlen($password ?? '') < 12 || strlen($password) > 72) {
                throw new \RuntimeException('Utilisez au moins 12 caractères et au maximum 72 octets.');
            }
            return $password;
        });
        $user = new User($email);
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $this->manager->persist($user);
        $this->manager->flush();
        $io->success('Administrateur créé. Connexion : /admin/login');

        return Command::SUCCESS;
    }
}
