<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230506175828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "users" (user_uuid UUID NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(150) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, user_photo_id VARCHAR(255) NOT NULL, date_of_birth TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_of_create TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, gender VARCHAR(6) NOT NULL, interests JSON NOT NULL, PRIMARY KEY(user_uuid))');
        $this->addSql('COMMENT ON COLUMN "users".user_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('DROP TABLE "user"');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE "user" (user_uuid UUID NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(150) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, user_photo_id VARCHAR(255) NOT NULL, date_of_birth TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_of_create TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, gender VARCHAR(6) NOT NULL, interests JSON NOT NULL, PRIMARY KEY(user_uuid))');
        $this->addSql('COMMENT ON COLUMN "user".user_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('DROP TABLE "users"');
    }
}
