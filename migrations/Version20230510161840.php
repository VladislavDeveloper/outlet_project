<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510161840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "posts" (post_uuid UUID NOT NULL, user_uuid UUID DEFAULT NULL, title VARCHAR(255) NOT NULL, body VARCHAR(255) NOT NULL, post_image_id VARCHAR(255) NOT NULL, date_of_create TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, interests JSON NOT NULL, likes JSON NOT NULL, dislikes JSON NOT NULL, PRIMARY KEY(post_uuid))');
        $this->addSql('CREATE INDEX IDX_885DBAFAABFE1C6F ON "posts" (user_uuid)');
        $this->addSql('COMMENT ON COLUMN "posts".post_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "posts".user_uuid IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE "posts" ADD CONSTRAINT FK_885DBAFAABFE1C6F FOREIGN KEY (user_uuid) REFERENCES "users" (user_uuid) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "posts" DROP CONSTRAINT FK_885DBAFAABFE1C6F');
        $this->addSql('DROP TABLE "posts"');
    }
}
