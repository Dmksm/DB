<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240116173932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product RENAME COLUMN descryption TO description');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product RENAME COLUMN description TO descryption');
    }
}
