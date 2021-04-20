<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210418092958 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cloth_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE location_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE wardrobe_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name VARCHAR(255) NOT NULL, temperature INT NOT NULL, weather VARCHAR(255) NOT NULL, rain_level VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cloth (id INT NOT NULL, color VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, fabric VARCHAR(255) NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE cloth_category (cloth_id INT NOT NULL, category_id INT NOT NULL, PRIMARY KEY(cloth_id, category_id))');
        $this->addSql('CREATE INDEX IDX_ADC66E13E53266EE ON cloth_category (cloth_id)');
        $this->addSql('CREATE INDEX IDX_ADC66E1312469DE2 ON cloth_category (category_id)');
        $this->addSql('CREATE TABLE location (id INT NOT NULL, country VARCHAR(255) NOT NULL, city VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE wardrobe (id INT NOT NULL, "user" VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE cloth_category ADD CONSTRAINT FK_ADC66E13E53266EE FOREIGN KEY (cloth_id) REFERENCES cloth (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cloth_category ADD CONSTRAINT FK_ADC66E1312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cloth_category DROP CONSTRAINT FK_ADC66E1312469DE2');
        $this->addSql('ALTER TABLE cloth_category DROP CONSTRAINT FK_ADC66E13E53266EE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cloth_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE location_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE wardrobe_id_seq CASCADE');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE cloth');
        $this->addSql('DROP TABLE cloth_category');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE wardrobe');
    }
}
