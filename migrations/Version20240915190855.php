<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240915190855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE schools (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, route_order INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE boxes ADD topic VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_91F646395E237E06 ON topics');
        $this->addSql('ALTER TABLE users ADD school VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE schools');
        $this->addSql('ALTER TABLE users DROP school');
        $this->addSql('ALTER TABLE boxes DROP topic');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_91F646395E237E06 ON topics (name)');
    }
}
