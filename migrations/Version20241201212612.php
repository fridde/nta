<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241201212612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking ADD nr_boxes INT NOT NULL, ADD nr_students INT NOT NULL, ADD period_id INT DEFAULT NULL, ADD topic_id VARCHAR(5) DEFAULT NULL, ADD booker_id VARCHAR(5) DEFAULT NULL');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEEC8B7ADE FOREIGN KEY (period_id) REFERENCES period (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE1F55203D FOREIGN KEY (topic_id) REFERENCES topics (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE8B7E4006 FOREIGN KEY (booker_id) REFERENCES topics (id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDEEC8B7ADE ON booking (period_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE1F55203D ON booking (topic_id)');
        $this->addSql('CREATE INDEX IDX_E00CEDDE8B7E4006 ON booking (booker_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEEC8B7ADE');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE1F55203D');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE8B7E4006');
        $this->addSql('DROP INDEX IDX_E00CEDDEEC8B7ADE ON booking');
        $this->addSql('DROP INDEX IDX_E00CEDDE1F55203D ON booking');
        $this->addSql('DROP INDEX IDX_E00CEDDE8B7E4006 ON booking');
        $this->addSql('ALTER TABLE booking DROP nr_boxes, DROP nr_students, DROP period_id, DROP topic_id, DROP booker_id');
    }
}
