<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241218203133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course_registration (id INT AUTO_INCREMENT NOT NULL, registered_at DATETIME NOT NULL, user_id INT DEFAULT NULL, topic_id VARCHAR(5) DEFAULT NULL, INDEX IDX_E362DF5AA76ED395 (user_id), INDEX IDX_E362DF5A1F55203D (topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE course_registration ADD CONSTRAINT FK_E362DF5AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE course_registration ADD CONSTRAINT FK_E362DF5A1F55203D FOREIGN KEY (topic_id) REFERENCES topics (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course_registration DROP FOREIGN KEY FK_E362DF5AA76ED395');
        $this->addSql('ALTER TABLE course_registration DROP FOREIGN KEY FK_E362DF5A1F55203D');
        $this->addSql('DROP TABLE course_registration');
    }
}
