<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231119123136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP name');
        $this->addSql('ALTER TABLE `order` CHANGE addres address VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE product_purchase DROP id_client, CHANGE order_date order_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE delivery_date delivery_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_purchase ADD id_client INT NOT NULL, CHANGE order_date order_date DATE NOT NULL, CHANGE delivery_date delivery_date DATE NOT NULL');
        $this->addSql('ALTER TABLE client ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE `order` CHANGE address addres VARCHAR(255) NOT NULL');
    }
}
