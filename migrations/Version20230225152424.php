<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230225152424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE journal_mood ADD moods_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE journal_mood ADD CONSTRAINT FK_BB01967E26A8295D FOREIGN KEY (moods_id) REFERENCES mood (id)');
        $this->addSql('CREATE INDEX IDX_BB01967E26A8295D ON journal_mood (moods_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE journal_mood DROP FOREIGN KEY FK_BB01967E26A8295D');
        $this->addSql('DROP INDEX IDX_BB01967E26A8295D ON journal_mood');
        $this->addSql('ALTER TABLE journal_mood DROP moods_id');
    }
}
