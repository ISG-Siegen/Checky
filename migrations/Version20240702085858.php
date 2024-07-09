<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702085858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE question_group_question (
          question_group_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          question_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          INDEX IDX_57E790C89D5C694B (question_group_id),
          INDEX IDX_57E790C81E27F6BF (question_id),
          PRIMARY KEY(question_group_id, question_id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          question_group_question
        ADD
          CONSTRAINT FK_57E790C89D5C694B FOREIGN KEY (question_group_id) REFERENCES question_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE
          question_group_question
        ADD
          CONSTRAINT FK_57E790C81E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E9D5C694B');
        $this->addSql('DROP INDEX IDX_B6F7494E9D5C694B ON question');
        $this->addSql('ALTER TABLE question DROP question_group_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question_group_question DROP FOREIGN KEY FK_57E790C89D5C694B');
        $this->addSql('ALTER TABLE question_group_question DROP FOREIGN KEY FK_57E790C81E27F6BF');
        $this->addSql('DROP TABLE question_group_question');
        $this->addSql('ALTER TABLE question ADD question_group_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE
          question
        ADD
          CONSTRAINT FK_B6F7494E9D5C694B FOREIGN KEY (question_group_id) REFERENCES question_group (id)');
        $this->addSql('CREATE INDEX IDX_B6F7494E9D5C694B ON question (question_group_id)');
    }
}
