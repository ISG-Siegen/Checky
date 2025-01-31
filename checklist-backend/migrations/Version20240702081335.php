<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240702081335 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE question (
          id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          question_group_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\',
          question LONGTEXT NOT NULL,
          answer_type VARCHAR(255) NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          INDEX IDX_B6F7494E9D5C694B (question_group_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_conference_instance (
          question_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          conference_instance_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          INDEX IDX_E6216B411E27F6BF (question_id),
          INDEX IDX_E6216B419FA55FC1 (conference_instance_id),
          PRIMARY KEY(
            question_id, conference_instance_id
          )
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_group (
          id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          description LONGTEXT NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_group_conference_instance (
          question_group_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          conference_instance_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          INDEX IDX_589B4B2E9D5C694B (question_group_id),
          INDEX IDX_589B4B2E9FA55FC1 (conference_instance_id),
          PRIMARY KEY(
            question_group_id, conference_instance_id
          )
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          question
        ADD
          CONSTRAINT FK_B6F7494E9D5C694B FOREIGN KEY (question_group_id) REFERENCES question_group (id)');
        $this->addSql('ALTER TABLE
          question_conference_instance
        ADD
          CONSTRAINT FK_E6216B411E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE
          question_conference_instance
        ADD
          CONSTRAINT FK_E6216B419FA55FC1 FOREIGN KEY (conference_instance_id) REFERENCES conference_instance (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE
          question_group_conference_instance
        ADD
          CONSTRAINT FK_589B4B2E9D5C694B FOREIGN KEY (question_group_id) REFERENCES question_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE
          question_group_conference_instance
        ADD
          CONSTRAINT FK_589B4B2E9FA55FC1 FOREIGN KEY (conference_instance_id) REFERENCES conference_instance (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494E9D5C694B');
        $this->addSql('ALTER TABLE question_conference_instance DROP FOREIGN KEY FK_E6216B411E27F6BF');
        $this->addSql('ALTER TABLE question_conference_instance DROP FOREIGN KEY FK_E6216B419FA55FC1');
        $this->addSql('ALTER TABLE question_group_conference_instance DROP FOREIGN KEY FK_589B4B2E9D5C694B');
        $this->addSql('ALTER TABLE question_group_conference_instance DROP FOREIGN KEY FK_589B4B2E9FA55FC1');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE question_conference_instance');
        $this->addSql('DROP TABLE question_group');
        $this->addSql('DROP TABLE question_group_conference_instance');
    }
}
