<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250118105238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE box_booking (box_id VARCHAR(8) NOT NULL, booking_id INT NOT NULL, INDEX IDX_F9CE73EFD8177B3F (box_id), INDEX IDX_F9CE73EF3301C60 (booking_id), PRIMARY KEY(box_id, booking_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE box_booking ADD CONSTRAINT FK_F9CE73EFD8177B3F FOREIGN KEY (box_id) REFERENCES boxes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE box_booking ADD CONSTRAINT FK_F9CE73EF3301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE box_booking DROP FOREIGN KEY FK_F9CE73EFD8177B3F');
        $this->addSql('ALTER TABLE box_booking DROP FOREIGN KEY FK_F9CE73EF3301C60');
        $this->addSql('DROP TABLE box_booking');
    }
}
