<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626085040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conference (
          id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          name VARCHAR(255) NOT NULL,
          description LONGTEXT DEFAULT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conference_instance (
          id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          conference_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          year SMALLINT NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          INDEX IDX_877177FF604B8382 (conference_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conference_instance_url (
          conference_instance_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          url_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          INDEX IDX_735E7A449FA55FC1 (conference_instance_id),
          INDEX IDX_735E7A4481CFDAE7 (url_id),
          PRIMARY KEY(conference_instance_id, url_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE url (
          id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          name VARCHAR(255) NOT NULL,
          url LONGTEXT NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          conference_instance
        ADD
          CONSTRAINT FK_877177FF604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id)');
        $this->addSql('ALTER TABLE
          conference_instance_url
        ADD
          CONSTRAINT FK_735E7A449FA55FC1 FOREIGN KEY (conference_instance_id) REFERENCES conference_instance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE
          conference_instance_url
        ADD
          CONSTRAINT FK_735E7A4481CFDAE7 FOREIGN KEY (url_id) REFERENCES url (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conference_instance DROP FOREIGN KEY FK_877177FF604B8382');
        $this->addSql('ALTER TABLE conference_instance_url DROP FOREIGN KEY FK_735E7A449FA55FC1');
        $this->addSql('ALTER TABLE conference_instance_url DROP FOREIGN KEY FK_735E7A4481CFDAE7');
        $this->addSql('DROP TABLE conference');
        $this->addSql('DROP TABLE conference_instance');
        $this->addSql('DROP TABLE conference_instance_url');
        $this->addSql('DROP TABLE url');
    }
}
