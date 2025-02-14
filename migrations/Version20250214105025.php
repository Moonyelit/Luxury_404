<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214105025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recruiter (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, society_name VARCHAR(50) DEFAULT NULL, activity VARCHAR(50) DEFAULT NULL, contact_name VARCHAR(50) DEFAULT NULL, job VARCHAR(255) DEFAULT NULL, phone_number VARCHAR(50) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, notes VARCHAR(255) DEFAULT NULL, date_created DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_DE8633D8A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recruiter ADD CONSTRAINT FK_DE8633D8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE candidate CHANGE completion_percentage completion_percentage INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recruiter DROP FOREIGN KEY FK_DE8633D8A76ED395');
        $this->addSql('DROP TABLE recruiter');
        $this->addSql('ALTER TABLE candidate CHANGE completion_percentage completion_percentage INT DEFAULT 0 NOT NULL');
    }
}
