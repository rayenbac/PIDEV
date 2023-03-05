<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
<<<<<<<< HEAD:migrations/Version20230302134940.php
final class Version20230302134940 extends AbstractMigration
========
final class Version20230304150341 extends AbstractMigration
>>>>>>>> origin/main:migrations/Version20230304150341.php
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
<<<<<<<< HEAD:migrations/Version20230302134940.php
        $this->addSql('ALTER TABLE mood ADD date DATETIME DEFAULT NULL');
========
        $this->addSql('ALTER TABLE user ADD reset_token VARCHAR(255) DEFAULT NULL, ADD reset_token_expires_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
>>>>>>>> origin/main:migrations/Version20230304150341.php
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
<<<<<<<< HEAD:migrations/Version20230302134940.php
        $this->addSql('ALTER TABLE mood DROP date');
========
        $this->addSql('ALTER TABLE user DROP reset_token, DROP reset_token_expires_at');
>>>>>>>> origin/main:migrations/Version20230304150341.php
    }
}
