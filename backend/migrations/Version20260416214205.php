<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260416214205 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contributor ADD person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contributor DROP contributor_order');
        $this->addSql('ALTER TABLE contributor ADD CONSTRAINT FK_DA6F9793217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DA6F9793217BBB47 ON contributor (person_id)');
        $this->addSql('ALTER TABLE student_profile ADD topic VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE student_profile ADD supervisor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student_profile ADD co_supervisor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student_profile DROP supervisor');
        $this->addSql('ALTER TABLE student_profile DROP co_supervisor');
        $this->addSql('ALTER TABLE student_profile ADD CONSTRAINT FK_6C611FF719E9AC5F FOREIGN KEY (supervisor_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE student_profile ADD CONSTRAINT FK_6C611FF78D8661E8 FOREIGN KEY (co_supervisor_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_6C611FF719E9AC5F ON student_profile (supervisor_id)');
        $this->addSql('CREATE INDEX IDX_6C611FF78D8661E8 ON student_profile (co_supervisor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contributor DROP CONSTRAINT FK_DA6F9793217BBB47');
        $this->addSql('DROP INDEX UNIQ_DA6F9793217BBB47');
        $this->addSql('ALTER TABLE contributor ADD contributor_order INT NOT NULL');
        $this->addSql('ALTER TABLE contributor DROP person_id');
        $this->addSql('ALTER TABLE student_profile DROP CONSTRAINT FK_6C611FF719E9AC5F');
        $this->addSql('ALTER TABLE student_profile DROP CONSTRAINT FK_6C611FF78D8661E8');
        $this->addSql('DROP INDEX IDX_6C611FF719E9AC5F');
        $this->addSql('DROP INDEX IDX_6C611FF78D8661E8');
        $this->addSql('ALTER TABLE student_profile ADD co_supervisor VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE student_profile DROP supervisor_id');
        $this->addSql('ALTER TABLE student_profile DROP co_supervisor_id');
        $this->addSql('ALTER TABLE student_profile RENAME COLUMN topic TO supervisor');
    }
}
