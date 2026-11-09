<?php

namespace App\Command;

use App\Entity\Blog;
use App\Repository\BlogRepository;
use App\Service\EditorialContent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:blog:import', description: 'Importer les articles existants et leurs traductions FR/EN sans écraser les articles déjà importés.')]
final class ImportBlogsCommand extends Command
{
    public function __construct(private EntityManagerInterface $manager, private BlogRepository $blogs, #[Autowire('%kernel.project_dir%')] private string $projectDir)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $catalogues = [];
        foreach (['fr', 'en'] as $locale) {
            $catalogues[$locale] = json_decode(file_get_contents($this->projectDir.'/translations/messages.'.$locale.'.json'), true, 512, JSON_THROW_ON_ERROR);
        }
        $imported = 0;
        foreach (EditorialContent::articleSeeds() as $entry) {
            if ($this->blogs->findOneBy(['reference' => $entry['id']])) continue;
            $translations = [];
            foreach ($catalogues as $locale => $messages) {
                $translations[$locale] = array_filter($messages, static fn (string $key): bool => str_starts_with($key, $entry['key'].'.'), ARRAY_FILTER_USE_KEY);
            }
            $this->manager->persist(new Blog($entry, $translations));
            ++$imported;
        }
        $this->manager->flush();
        (new SymfonyStyle($input, $output))->success($imported.' article(s) importé(s). Les articles déjà présents sont conservés.');

        return Command::SUCCESS;
    }
}
