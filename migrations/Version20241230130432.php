<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241230130432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE box_status_update (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, date DATETIME(6) NOT NULL, box_id VARCHAR(8) DEFAULT NULL, INDEX IDX_4BCC6E3ED8177B3F (box_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE inventory_status_update (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE box_status_update ADD CONSTRAINT FK_4BCC6E3ED8177B3F FOREIGN KEY (box_id) REFERENCES boxes (id)');
        $this->addSql('ALTER TABLE status_update DROP FOREIGN KEY FK_256F9D35D8177B3F');
        $this->addSql('DROP TABLE status_update');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE status_update (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date DATETIME(6) NOT NULL, box_id VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, INDEX IDX_256F9D35D8177B3F (box_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE status_update ADD CONSTRAINT FK_256F9D35D8177B3F FOREIGN KEY (box_id) REFERENCES boxes (id)');
        $this->addSql('ALTER TABLE box_status_update DROP FOREIGN KEY FK_4BCC6E3ED8177B3F');
        $this->addSql('DROP TABLE box_status_update');
        $this->addSql('DROP TABLE inventory_status_update');
    }
}
