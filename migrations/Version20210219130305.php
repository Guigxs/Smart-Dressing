<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210219130305 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, temperature INT NOT NULL, weather VARCHAR(255) NOT NULL, rain_level VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cloth_category (cloth_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_ADC66E13E53266EE (cloth_id), INDEX IDX_ADC66E1312469DE2 (category_id), PRIMARY KEY(cloth_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, country VARCHAR(255) NOT NULL, city VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wardrobe (id INT AUTO_INCREMENT NOT NULL, user VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cloth_category ADD CONSTRAINT FK_ADC66E13E53266EE FOREIGN KEY (cloth_id) REFERENCES cloth (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cloth_category ADD CONSTRAINT FK_ADC66E1312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cloth ADD name VARCHAR(255) NOT NULL, ADD fabric VARCHAR(255) NOT NULL, ADD quantity INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cloth_category DROP FOREIGN KEY FK_ADC66E1312469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE cloth_category');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE wardrobe');
        $this->addSql('ALTER TABLE cloth DROP name, DROP fabric, DROP quantity');
    }
}
