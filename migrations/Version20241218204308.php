<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241218204308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_registration CHANGE registered_at registered_at VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE status_update ADD type VARCHAR(255) NOT NULL, ADD date VARCHAR(255) NOT NULL, ADD box_id VARCHAR(8) DEFAULT NULL');
        $this->addSql('ALTER TABLE status_update ADD CONSTRAINT FK_256F9D35D8177B3F FOREIGN KEY (box_id) REFERENCES boxes (id)');
        $this->addSql('CREATE INDEX IDX_256F9D35D8177B3F ON status_update (box_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE status_update DROP FOREIGN KEY FK_256F9D35D8177B3F');
        $this->addSql('DROP INDEX IDX_256F9D35D8177B3F ON status_update');
        $this->addSql('ALTER TABLE status_update DROP type, DROP date, DROP box_id');
        $this->addSql('ALTER TABLE course_registration CHANGE registered_at registered_at DATETIME NOT NULL');
    }
}
