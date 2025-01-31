<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240717111918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE term (
          id INT AUTO_INCREMENT NOT NULL,
          term VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE term_frequency (
          id INT AUTO_INCREMENT NOT NULL,
          question_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          term_id INT NOT NULL,
          frequency INT NOT NULL,
          INDEX IDX_EAFC99F81E27F6BF (question_id),
          INDEX IDX_EAFC99F8E2C35FC (term_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          term_frequency
        ADD
          CONSTRAINT FK_EAFC99F81E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE
          term_frequency
        ADD
          CONSTRAINT FK_EAFC99F8E2C35FC FOREIGN KEY (term_id) REFERENCES term (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE term_frequency DROP FOREIGN KEY FK_EAFC99F81E27F6BF');
        $this->addSql('ALTER TABLE term_frequency DROP FOREIGN KEY FK_EAFC99F8E2C35FC');
        $this->addSql('DROP TABLE term');
        $this->addSql('DROP TABLE term_frequency');
    }
}
