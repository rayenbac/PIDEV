<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221182506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE journal_mood DROP FOREIGN KEY FK_BB01967E26A8295D');
        $this->addSql('DROP TABLE journal_mood');
        $this->addSql('ALTER TABLE mood CHANGE user_id user_id INT NOT NULL, CHANGE mood mood VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE mood_id mood_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE journal_mood (moods_id INT DEFAULT NULL, INDEX IDX_BB01967E26A8295D (moods_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE journal_mood ADD CONSTRAINT FK_BB01967E26A8295D FOREIGN KEY (moods_id) REFERENCES mood (id)');
        $this->addSql('ALTER TABLE mood CHANGE mood_id mood_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE mood mood VARCHAR(255) DEFAULT NULL, CHANGE description description VARCHAR(255) DEFAULT NULL');
    }
}
