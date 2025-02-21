<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241018110337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email_notification (id BIGINT GENERATED BY DEFAULT AS IDENTITY NOT NULL, email VARCHAR(128) NOT NULL, text VARCHAR(512) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE feed (id BIGINT GENERATED BY DEFAULT AS IDENTITY NOT NULL, tweets JSON DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, reader_id BIGINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_234044AB1717D737 ON feed (reader_id)');
        $this->addSql('CREATE TABLE sms_notification (id BIGINT GENERATED BY DEFAULT AS IDENTITY NOT NULL, phone VARCHAR(11) NOT NULL, text VARCHAR(60) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE feed ADD CONSTRAINT FK_234044AB1717D737 FOREIGN KEY (reader_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE feed DROP CONSTRAINT FK_234044AB1717D737');
        $this->addSql('DROP TABLE email_notification');
        $this->addSql('DROP TABLE feed');
        $this->addSql('DROP TABLE sms_notification');
    }
}
