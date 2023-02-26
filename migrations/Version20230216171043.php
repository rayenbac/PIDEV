<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
<<<<<<<< HEAD:migrations/Version20230221171010.php
final class Version20230221171010 extends AbstractMigration
========
final class Version20230216171043 extends AbstractMigration
>>>>>>>> facd8e617b97f6721b10e43c64df418237be324d:migrations/Version20230216171043.php
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
<<<<<<<< HEAD:migrations/Version20230221171010.php
        $this->addSql('ALTER TABLE user CHANGE phone_number phone_number INT NOT NULL');
========
        $this->addSql('ALTER TABLE product ADD quantity INT NOT NULL, ADD created_at DATETIME DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL');
>>>>>>>> facd8e617b97f6721b10e43c64df418237be324d:migrations/Version20230216171043.php
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
<<<<<<<< HEAD:migrations/Version20230221171010.php
        $this->addSql('ALTER TABLE user CHANGE phone_number phone_number VARCHAR(255) NOT NULL');
========
        $this->addSql('ALTER TABLE product DROP quantity, DROP created_at, DROP updated_at');
>>>>>>>> facd8e617b97f6721b10e43c64df418237be324d:migrations/Version20230216171043.php
    }
}
