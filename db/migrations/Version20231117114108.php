<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117114108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'insert first staff member';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO staff_info (id, position, first_name, last_name, email, password, birthday, telephone) VALUES(1, 'admin', 'Admin', 'Adminov', 'admin@gmail.com', 'Admin1', '1970-01-01', '+79078001234')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM staff_info WHERE id = 1');
    }
}