<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260208222702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contributor (id INT AUTO_INCREMENT NOT NULL, contributor_order INT NOT NULL, display_name VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, content VARCHAR(2500) NOT NULL, title VARCHAR(255) NOT NULL, event_type VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE institution (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, content VARCHAR(2500) NOT NULL, published_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, image_path VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, biography VARCHAR(2500) DEFAULT NULL, is_active TINYINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(180) DEFAULT NULL, photo_path VARCHAR(255) DEFAULT NULL, job_title VARCHAR(150) DEFAULT NULL, personal_page_url VARCHAR(255) DEFAULT NULL, role LONGTEXT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, summary VARCHAR(2500) DEFAULT NULL, funding_source VARCHAR(255) DEFAULT NULL, is_finished TINYINT NOT NULL, title VARCHAR(255) NOT NULL, thematic VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, year INT NOT NULL, external_url VARCHAR(255) DEFAULT NULL, publication_type VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE student_degree (id INT AUTO_INCREMENT NOT NULL, start_year INT NOT NULL, end_year INT DEFAULT NULL, degree VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE student_profile (id INT AUTO_INCREMENT NOT NULL, supervisor VARCHAR(255) DEFAULT NULL, co_supervisor VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contributor');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE institution');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE student_degree');
        $this->addSql('DROP TABLE student_profile');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
