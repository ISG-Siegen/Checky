<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240612084246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE checklist (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_5C696D2F5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE checklist_source (checklist_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', source_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_3EDF6827B16D08A7 (checklist_id), INDEX IDX_3EDF6827953C1C61 (source_id), PRIMARY KEY(checklist_id, source_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE source (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, year SMALLINT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_5F8A7F735E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE url (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', checklist_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, url LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F47645AEB16D08A7 (checklist_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE checklist_source ADD CONSTRAINT FK_3EDF6827B16D08A7 FOREIGN KEY (checklist_id) REFERENCES checklist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE checklist_source ADD CONSTRAINT FK_3EDF6827953C1C61 FOREIGN KEY (source_id) REFERENCES source (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE url ADD CONSTRAINT FK_F47645AEB16D08A7 FOREIGN KEY (checklist_id) REFERENCES checklist (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE checklist_source DROP FOREIGN KEY FK_3EDF6827B16D08A7');
        $this->addSql('ALTER TABLE checklist_source DROP FOREIGN KEY FK_3EDF6827953C1C61');
        $this->addSql('ALTER TABLE url DROP FOREIGN KEY FK_F47645AEB16D08A7');
        $this->addSql('DROP TABLE checklist');
        $this->addSql('DROP TABLE checklist_source');
        $this->addSql('DROP TABLE source');
        $this->addSql('DROP TABLE url');
    }
}
