<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260416234944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP CONSTRAINT fk_34dcd1762125ff59');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD1762125FF59 FOREIGN KEY (student_profile_id) REFERENCES student_profile (id) ON DELETE SET NULL NOT DEFERRABLE');
        $this->addSql('ALTER TABLE student_profile DROP CONSTRAINT fk_6c611ff719e9ac5f');
        $this->addSql('ALTER TABLE student_profile DROP CONSTRAINT fk_6c611ff78d8661e8');
        $this->addSql('ALTER TABLE student_profile ADD CONSTRAINT FK_6C611FF719E9AC5F FOREIGN KEY (supervisor_id) REFERENCES person (id) ON DELETE SET NULL NOT DEFERRABLE');
        $this->addSql('ALTER TABLE student_profile ADD CONSTRAINT FK_6C611FF78D8661E8 FOREIGN KEY (co_supervisor_id) REFERENCES person (id) ON DELETE SET NULL NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP CONSTRAINT FK_34DCD1762125FF59');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT fk_34dcd1762125ff59 FOREIGN KEY (student_profile_id) REFERENCES student_profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_profile DROP CONSTRAINT FK_6C611FF719E9AC5F');
        $this->addSql('ALTER TABLE student_profile DROP CONSTRAINT FK_6C611FF78D8661E8');
        $this->addSql('ALTER TABLE student_profile ADD CONSTRAINT fk_6c611ff719e9ac5f FOREIGN KEY (supervisor_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_profile ADD CONSTRAINT fk_6c611ff78d8661e8 FOREIGN KEY (co_supervisor_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
