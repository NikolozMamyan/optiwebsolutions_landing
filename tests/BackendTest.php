<?php

namespace App\Tests;

use App\Entity\Blog;
use App\Entity\Prospect;
use App\Entity\User;
use App\Kernel;
use App\Service\EditorialContent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class BackendTest extends WebTestCase
{
    private const EMAIL = 'backend-test-admin@example.invalid';
    private const PASSWORD = 'Only-for-backend-tests-2026!';
    private KernelBrowser $client;
    private User $reader;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $manager = static::getContainer()->get(EntityManagerInterface::class);
        $connection = $manager->getConnection();
        self::assertStringEndsWith('_backend_test', $connection->getDatabase(), 'Tests must use the isolated backend test database.');
        $connection->executeStatement("DELETE FROM prospect WHERE email LIKE 'backend-test-%@example.invalid'");
        $connection->executeStatement("DELETE FROM app_user WHERE email LIKE 'backend-test-%@example.invalid'");
        static::getContainer()->get('cache.rate_limiter')->clear();
        $hasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $admin = new User(self::EMAIL);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($hasher->hashPassword($admin, self::PASSWORD));
        $this->reader = new User('backend-test-reader@example.invalid');
        $this->reader->setPassword($hasher->hashPassword($this->reader, self::PASSWORD));
        $manager->persist($admin);
        $manager->persist($this->reader);
        $manager->flush();
        self::assertNotSame(self::PASSWORD, $admin->getPassword());
        self::assertStringNotContainsString($admin->getPassword(), serialize($admin));
    }

    private function login(KernelBrowser $client, string $password = self::PASSWORD): void
    {
        $client->request('GET', '/admin/login');
        $client->submitForm('Se connecter', ['email' => self::EMAIL, 'password' => $password]);
    }

    private function contactToken(): string
    {
        $crawler = $this->client->request('GET', '/');
        self::assertResponseIsSuccessful();

        return $crawler->filter('.contact-modal-form input[name="_token"]')->attr('value');
    }

    private function message(string $token): array
    {
        return ['_token' => $token, 'firstName' => 'Camille', 'lastName' => 'Dupont', 'email' => 'backend-test-prospect@example.invalid'];
    }

    public function testAdminAccessAndPasswordLogin(): void
    {
        $this->client->request('GET', '/admin/prospects');
        self::assertResponseRedirects();
        self::assertSame('/admin/login', parse_url($this->client->getResponse()->headers->get('Location'), PHP_URL_PATH));
        $this->login($this->client);
        $this->client->request('GET', '/admin');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Tableau de bord');
        self::assertStringContainsString('no-store', $this->client->getResponse()->headers->get('Cache-Control'));
    }

    public function testAdministratorCommandRejectsInvalidEmailAndDuplicates(): void
    {
        $application = new Application(static::$kernel);
        $tester = new CommandTester($application->find('app:admin:create'));
        $tester->execute(['email' => 'invalid'], ['interactive' => false]);
        self::assertSame(2, $tester->getStatusCode());
        $tester->execute(['email' => self::EMAIL], ['interactive' => false]);
        self::assertSame(1, $tester->getStatusCode());
        $tester->execute(['email' => 'backend-test-command@example.invalid'], ['interactive' => false]);
        self::assertSame(2, $tester->getStatusCode());
        self::assertNull(static::getContainer()->get(EntityManagerInterface::class)->getRepository(User::class)->findOneBy(['email' => 'backend-test-command@example.invalid']));
    }

    public function testNonAdminCannotReadProspects(): void
    {
        $this->client->loginUser($this->reader);
        $this->client->request('GET', '/admin/prospects');
        self::assertResponseStatusCodeSame(403);
    }

    public function testLoginCsrfAndThrottling(): void
    {
        $this->client->request('POST', '/admin/login', ['email' => self::EMAIL, 'password' => self::PASSWORD, '_csrf_token' => 'forged']);
        $this->client->request('GET', '/admin');
        self::assertResponseRedirects();
        for ($attempt = 0; $attempt < 6; ++$attempt) $this->login($this->client, 'incorrect-password');
        $this->login($this->client);
        self::assertSame('/admin/login', parse_url($this->client->getResponse()->headers->get('Location'), PHP_URL_PATH));
        $this->client->request('GET', '/admin');
        self::assertResponseRedirects();
    }

    public function testConcurrentSessionsAndScopedLogout(): void
    {
        $this->login($this->client);
        $second = new KernelBrowser(new Kernel('test', true));
        $this->login($second);
        $this->client->request('GET', '/admin');
        self::assertResponseIsSuccessful();
        $crawler = $this->client->getCrawler();
        $token = $crawler->filter('form[action="/admin/logout"] input')->attr('value');
        $this->client->request('POST', '/admin/logout', ['_csrf_token' => 'forged']);
        self::assertResponseStatusCodeSame(403);
        $this->client->request('POST', '/admin/logout', ['_csrf_token' => $token]);
        $this->client->request('GET', '/admin');
        self::assertResponseRedirects();
        $second->request('GET', '/admin');
        self::assertSame(200, $second->getResponse()->getStatusCode());
    }

    public function testProspectValidationAndRealPersistence(): void
    {
        $token = $this->contactToken();
        $data = $this->message($token);
        $this->client->request('POST', '/contact/demande', [...$data, '_token' => 'forged']);
        self::assertResponseStatusCodeSame(403);
        $this->client->request('POST', '/contact/demande', [...$data, 'firstName' => ' ', 'email' => 'invalid']);
        self::assertResponseStatusCodeSame(422);
        $this->client->request('POST', '/contact/demande', [...$data, 'phone' => ['invalid']]);
        self::assertResponseStatusCodeSame(422);
        $this->client->request('POST', '/contact/demande', $data);
        self::assertResponseStatusCodeSame(201);
        $prospect = static::getContainer()->get(EntityManagerInterface::class)->getRepository(Prospect::class)->findOneBy(['email' => $data['email']]);
        self::assertNotNull($prospect);
        self::assertSame('Camille', $prospect->getFirstName());
        self::assertSame('Dupont', $prospect->getLastName());
        self::assertNull($prospect->getPhone());
        self::assertNull($prospect->getMessage());
    }

    public function testContactRateLimitDoesNotPersistExtraRequest(): void
    {
        $data = $this->message($this->contactToken());
        for ($i = 0; $i < 5; ++$i) {
            $this->client->request('POST', '/contact/demande', $data);
            self::assertResponseStatusCodeSame(201);
        }
        $this->client->request('POST', '/contact/demande', $data);
        self::assertResponseStatusCodeSame(429);
        self::assertSame(5, static::getContainer()->get(EntityManagerInterface::class)->getRepository(Prospect::class)->count(['email' => $data['email']]));
    }

    public function testProspectOutputIsEscapedAndCopyIncludesAllFields(): void
    {
        $data = [...$this->message($this->contactToken()), 'phone' => '+33600000000', 'message' => '<script>alert("x")</script>'."\nSecond line"];
        $this->client->request('POST', '/contact/demande', $data);
        self::assertResponseStatusCodeSame(201);
        $this->login($this->client);
        $crawler = $this->client->request('GET', '/admin/prospects');
        self::assertResponseIsSuccessful();
        self::assertSame($data['message'], $crawler->filter('.prospect-message p')->text(null, false));
        self::assertStringNotContainsString('<script>alert', $this->client->getResponse()->getContent());
        $copy = $crawler->filter('[data-copy-target="text"]')->text(null, false);
        self::assertStringContainsString('Prénom : Camille', $copy);
        self::assertStringContainsString('Nom : Dupont', $copy);
        self::assertStringContainsString($data['email'], $copy);
        self::assertStringContainsString("\n    Second line", $copy);
    }

    public function testBlogImportIsIdempotentAndPublicContentComesFromDatabase(): void
    {
        $application = new Application(static::$kernel);
        $command = new CommandTester($application->find('app:blog:import'));
        $command->execute([]);
        $repository = static::getContainer()->get(EntityManagerInterface::class)->getRepository(Blog::class);
        self::assertSame(count(EditorialContent::articleSeeds()), $repository->count([]));
        $command->execute([]);
        self::assertStringContainsString('0 article(s)', $command->getDisplay());
        $blog = $repository->findOneBy(['reference' => 'wordpress_symfony']);
        $original = $blog->getTranslations();
        self::assertNotEmpty($original['fr']);
        self::assertNotEmpty($original['en']);
        $messages = $original;
        $messages['fr']['blog.wordpress_symfony.title'] = 'Titre provenant de la base';
        $connection = static::getContainer()->get(EntityManagerInterface::class)->getConnection();
        $connection->update('blog', ['translations' => json_encode($messages, JSON_THROW_ON_ERROR)], ['id' => $blog->getId()]);
        static::getContainer()->get(EntityManagerInterface::class)->clear();
        try {
            $this->client->request('GET', '/blog/wordpress-ou-symfony-quel-choix-projet-web');
            self::assertResponseIsSuccessful();
            self::assertSelectorTextContains('h1', 'Titre provenant de la base');
        } finally {
            $connection->update('blog', ['translations' => json_encode($original, JSON_THROW_ON_ERROR)], ['id' => $blog->getId()]);
        }
    }
}
