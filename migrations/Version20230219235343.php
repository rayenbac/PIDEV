<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230219235343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE journal_mood (id INT AUTO_INCREMENT NOT NULL, moods_id INT DEFAULT NULL, id_journal INT NOT NULL, id_user INT NOT NULL, INDEX IDX_BB01967E26A8295D (moods_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE journal_mood ADD CONSTRAINT FK_BB01967E26A8295D FOREIGN KEY (moods_id) REFERENCES mood (id)');
        $this->addSql('ALTER TABLE journalmood DROP FOREIGN KEY FK_CBCF303BB889D33E');
        $this->addSql('DROP TABLE journalmood');
        $this->addSql('ALTER TABLE mood ADD mood_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE journalmood (id INT AUTO_INCREMENT NOT NULL, mood_id INT DEFAULT NULL, id_user INT NOT NULL, INDEX IDX_CBCF303BB889D33E (mood_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE journalmood ADD CONSTRAINT FK_CBCF303BB889D33E FOREIGN KEY (mood_id) REFERENCES mood (id)');
        $this->addSql('ALTER TABLE journal_mood DROP FOREIGN KEY FK_BB01967E26A8295D');
        $this->addSql('DROP TABLE journal_mood');
        $this->addSql('ALTER TABLE mood DROP mood_id');
    }
}
