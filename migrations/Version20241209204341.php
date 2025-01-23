<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241209204341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item CHANGE placement placement VARCHAR(255) DEFAULT NULL, CHANGE detailed_label detailed_label VARCHAR(255) DEFAULT NULL, CHANGE simple_label simple_label VARCHAR(255) DEFAULT NULL, CHANGE staff_info staff_info VARCHAR(255) DEFAULT NULL, CHANGE user_info user_info VARCHAR(255) DEFAULT NULL, CHANGE order_info order_info JSON DEFAULT NULL, CHANGE list_rank list_rank INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item CHANGE placement placement VARCHAR(255) NOT NULL, CHANGE detailed_label detailed_label VARCHAR(255) NOT NULL, CHANGE simple_label simple_label VARCHAR(255) NOT NULL, CHANGE staff_info staff_info VARCHAR(255) NOT NULL, CHANGE user_info user_info VARCHAR(255) NOT NULL, CHANGE order_info order_info JSON NOT NULL, CHANGE list_rank list_rank INT NOT NULL');
    }
}
