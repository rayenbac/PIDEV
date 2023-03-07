<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230305142902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cabinet ADD cabinetmedecin_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cabinet ADD CONSTRAINT FK_4CED05B05BECFB4F FOREIGN KEY (cabinetmedecin_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4CED05B05BECFB4F ON cabinet (cabinetmedecin_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cabinet DROP FOREIGN KEY FK_4CED05B05BECFB4F');
        $this->addSql('DROP INDEX UNIQ_4CED05B05BECFB4F ON cabinet');
        $this->addSql('ALTER TABLE cabinet DROP cabinetmedecin_id');
    }
}
