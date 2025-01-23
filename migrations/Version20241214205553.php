<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241214205553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory ADD inventory_type INT NOT NULL, ADD quantity INT NOT NULL, ADD list_rank INT NOT NULL, ADD item_id VARCHAR(3) DEFAULT NULL, ADD topic_id VARCHAR(5) DEFAULT NULL');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A36126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A361F55203D FOREIGN KEY (topic_id) REFERENCES topics (id)');
        $this->addSql('CREATE INDEX IDX_B12D4A36126F525E ON inventory (item_id)');
        $this->addSql('CREATE INDEX IDX_B12D4A361F55203D ON inventory (topic_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A36126F525E');
        $this->addSql('ALTER TABLE inventory DROP FOREIGN KEY FK_B12D4A361F55203D');
        $this->addSql('DROP INDEX IDX_B12D4A36126F525E ON inventory');
        $this->addSql('DROP INDEX IDX_B12D4A361F55203D ON inventory');
        $this->addSql('ALTER TABLE inventory DROP inventory_type, DROP quantity, DROP list_rank, DROP item_id, DROP topic_id');
    }
}
