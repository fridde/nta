<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241230195631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory_status_update ADD sufficient TINYINT(1) NOT NULL, ADD date DATETIME(6) NOT NULL, ADD item_id VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE inventory_status_update ADD CONSTRAINT FK_46625EC1126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_46625EC1126F525E ON inventory_status_update (item_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE inventory_status_update DROP FOREIGN KEY FK_46625EC1126F525E');
        $this->addSql('DROP INDEX IDX_46625EC1126F525E ON inventory_status_update');
        $this->addSql('ALTER TABLE inventory_status_update DROP sufficient, DROP date, DROP item_id');
    }
}
