<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240716080630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE saved_checklist (
          id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          name VARCHAR(255) NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          updated_at DATETIME NOT NULL,
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE saved_question (
          id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          original_question_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\',
          saved_checklist_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\',
          question LONGTEXT NOT NULL,
          answer_type VARCHAR(255) NOT NULL,
          INDEX IDX_D8164E66E9281695 (original_question_id),
          INDEX IDX_D8164E663CCB1701 (saved_checklist_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          saved_question
        ADD
          CONSTRAINT FK_D8164E66E9281695 FOREIGN KEY (original_question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE
          saved_question
        ADD
          CONSTRAINT FK_D8164E663CCB1701 FOREIGN KEY (saved_checklist_id) REFERENCES saved_checklist (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE saved_question DROP FOREIGN KEY FK_D8164E66E9281695');
        $this->addSql('ALTER TABLE saved_question DROP FOREIGN KEY FK_D8164E663CCB1701');
        $this->addSql('DROP TABLE saved_checklist');
        $this->addSql('DROP TABLE saved_question');
    }
}
