<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230305130356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendez_vous ADD usermed_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A11C42EA8 FOREIGN KEY (usermed_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_65E8AA0A11C42EA8 ON rendez_vous (usermed_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A11C42EA8');
        $this->addSql('DROP INDEX IDX_65E8AA0A11C42EA8 ON rendez_vous');
        $this->addSql('ALTER TABLE rendez_vous DROP usermed_id');
    }
}
