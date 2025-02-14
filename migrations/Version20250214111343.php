<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250214111343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE apply (id INT AUTO_INCREMENT NOT NULL, candidate_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BD2F8C1F91BD8781 (candidate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job_offer (id INT AUTO_INCREMENT NOT NULL, recruiter_id INT DEFAULT NULL, job_category_id INT DEFAULT NULL, reference INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, is_activate TINYINT(1) DEFAULT NULL, notes VARCHAR(255) DEFAULT NULL, address VARCHAR(50) DEFAULT NULL, country VARCHAR(50) DEFAULT NULL, date_start DATE DEFAULT NULL, date_closing DATE DEFAULT NULL, salary INT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', update_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_288A3A4E156BE243 (recruiter_id), INDEX IDX_288A3A4E712A86AB (job_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE apply ADD CONSTRAINT FK_BD2F8C1F91BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E156BE243 FOREIGN KEY (recruiter_id) REFERENCES recruiter (id)');
        $this->addSql('ALTER TABLE job_offer ADD CONSTRAINT FK_288A3A4E712A86AB FOREIGN KEY (job_category_id) REFERENCES job_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE apply DROP FOREIGN KEY FK_BD2F8C1F91BD8781');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E156BE243');
        $this->addSql('ALTER TABLE job_offer DROP FOREIGN KEY FK_288A3A4E712A86AB');
        $this->addSql('DROP TABLE apply');
        $this->addSql('DROP TABLE job_offer');
    }
}
