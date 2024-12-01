<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241103202729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE boxes (id VARCHAR(255) NOT NULL, topic_id VARCHAR(5) DEFAULT NULL, INDEX IDX_CDF1B2E91F55203D (topic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE schools (id VARCHAR(8) NOT NULL, name VARCHAR(255) NOT NULL, route_order INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE topics (id VARCHAR(5) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) NOT NULL, school_id VARCHAR(8) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E95126AC48 (mail), INDEX IDX_1483A5E9C32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE boxes ADD CONSTRAINT FK_CDF1B2E91F55203D FOREIGN KEY (topic_id) REFERENCES topics (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9C32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boxes DROP FOREIGN KEY FK_CDF1B2E91F55203D');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9C32A47EE');
        $this->addSql('DROP TABLE boxes');
        $this->addSql('DROP TABLE schools');
        $this->addSql('DROP TABLE topics');
        $this->addSql('DROP TABLE users');
    }
}
