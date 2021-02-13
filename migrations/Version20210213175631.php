<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210213175631 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cloth_category (cloth_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_ADC66E13E53266EE (cloth_id), INDEX IDX_ADC66E1312469DE2 (category_id), PRIMARY KEY(cloth_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cloth_category ADD CONSTRAINT FK_ADC66E13E53266EE FOREIGN KEY (cloth_id) REFERENCES cloth (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cloth_category ADD CONSTRAINT FK_ADC66E1312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cloth_category');
    }
}
