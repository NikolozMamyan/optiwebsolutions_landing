<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261109095155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Administrateurs, articles, prospects et sessions persistantes.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_88BDF3E9E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE blog (id INT AUTO_INCREMENT NOT NULL, reference VARCHAR(80) NOT NULL, metadata JSON NOT NULL, translations JSON NOT NULL, UNIQUE INDEX UNIQ_C0155143AEA34913 (reference), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE prospect (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(40) DEFAULT NULL, message LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_C9CE8C7D8B8E8428 (created_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE app_sessions (sess_id VARBINARY(128) NOT NULL, sess_data LONGBLOB NOT NULL, sess_lifetime INT UNSIGNED NOT NULL, sess_time INT UNSIGNED NOT NULL, INDEX sess_lifetime_idx (sess_lifetime), PRIMARY KEY (sess_id)) ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE blog');
        $this->addSql('DROP TABLE prospect');
        $this->addSql('DROP TABLE app_sessions');
    }
}
