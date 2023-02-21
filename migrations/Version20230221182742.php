<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221182742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE journal_mood (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mood ADD journal_mood_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mood ADD CONSTRAINT FK_339AEF668329DDB FOREIGN KEY (journal_mood_id) REFERENCES journal_mood (id)');
        $this->addSql('CREATE INDEX IDX_339AEF668329DDB ON mood (journal_mood_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mood DROP FOREIGN KEY FK_339AEF668329DDB');
        $this->addSql('DROP TABLE journal_mood');
        $this->addSql('DROP INDEX IDX_339AEF668329DDB ON mood');
        $this->addSql('ALTER TABLE mood DROP journal_mood_id');
    }
}
