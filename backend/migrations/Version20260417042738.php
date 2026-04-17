<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260417042738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contributor_publication (contributor_id INT NOT NULL, publication_id INT NOT NULL, PRIMARY KEY (contributor_id, publication_id))');
        $this->addSql('CREATE INDEX IDX_BB29F5E67A19A357 ON contributor_publication (contributor_id)');
        $this->addSql('CREATE INDEX IDX_BB29F5E638B217A7 ON contributor_publication (publication_id)');
        $this->addSql('CREATE TABLE contributor_project (contributor_id INT NOT NULL, project_id INT NOT NULL, PRIMARY KEY (contributor_id, project_id))');
        $this->addSql('CREATE INDEX IDX_EA7C10447A19A357 ON contributor_project (contributor_id)');
        $this->addSql('CREATE INDEX IDX_EA7C1044166D1F9C ON contributor_project (project_id)');
        $this->addSql('CREATE TABLE person_departement (person_id INT NOT NULL, departement_id INT NOT NULL, PRIMARY KEY (person_id, departement_id))');
        $this->addSql('CREATE INDEX IDX_AC9D04EB217BBB47 ON person_departement (person_id)');
        $this->addSql('CREATE INDEX IDX_AC9D04EBCCF9E01E ON person_departement (departement_id)');
        $this->addSql('CREATE TABLE person_institution (person_id INT NOT NULL, institution_id INT NOT NULL, PRIMARY KEY (person_id, institution_id))');
        $this->addSql('CREATE INDEX IDX_5774C76D217BBB47 ON person_institution (person_id)');
        $this->addSql('CREATE INDEX IDX_5774C76D10405986 ON person_institution (institution_id)');
        $this->addSql('ALTER TABLE contributor_publication ADD CONSTRAINT FK_BB29F5E67A19A357 FOREIGN KEY (contributor_id) REFERENCES contributor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contributor_publication ADD CONSTRAINT FK_BB29F5E638B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contributor_project ADD CONSTRAINT FK_EA7C10447A19A357 FOREIGN KEY (contributor_id) REFERENCES contributor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contributor_project ADD CONSTRAINT FK_EA7C1044166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_departement ADD CONSTRAINT FK_AC9D04EB217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_departement ADD CONSTRAINT FK_AC9D04EBCCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_institution ADD CONSTRAINT FK_5774C76D217BBB47 FOREIGN KEY (person_id) REFERENCES person (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person_institution ADD CONSTRAINT FK_5774C76D10405986 FOREIGN KEY (institution_id) REFERENCES institution (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE person DROP CONSTRAINT fk_34dcd1762125ff59');
        $this->addSql('ALTER TABLE person DROP CONSTRAINT fk_34dcd17610405986');
        $this->addSql('ALTER TABLE person DROP CONSTRAINT fk_34dcd176ccf9e01e');
        $this->addSql('DROP INDEX uniq_34dcd176ccf9e01e');
        $this->addSql('DROP INDEX uniq_34dcd17610405986');
        $this->addSql('ALTER TABLE person DROP institution_id');
        $this->addSql('ALTER TABLE person DROP departement_id');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD1762125FF59 FOREIGN KEY (student_profile_id) REFERENCES student_profile (id) ON DELETE SET NULL NOT DEFERRABLE');
        $this->addSql('ALTER TABLE publication DROP CONSTRAINT fk_af3c67797a19a357');
        $this->addSql('DROP INDEX idx_af3c67797a19a357');
        $this->addSql('ALTER TABLE publication DROP contributor_id');
        $this->addSql('ALTER TABLE student_profile DROP CONSTRAINT fk_6c611ff719e9ac5f');
        $this->addSql('ALTER TABLE student_profile DROP CONSTRAINT fk_6c611ff78d8661e8');
        $this->addSql('ALTER TABLE student_profile ADD CONSTRAINT FK_6C611FF719E9AC5F FOREIGN KEY (supervisor_id) REFERENCES person (id) ON DELETE SET NULL NOT DEFERRABLE');
        $this->addSql('ALTER TABLE student_profile ADD CONSTRAINT FK_6C611FF78D8661E8 FOREIGN KEY (co_supervisor_id) REFERENCES person (id) ON DELETE SET NULL NOT DEFERRABLE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contributor_publication DROP CONSTRAINT FK_BB29F5E67A19A357');
        $this->addSql('ALTER TABLE contributor_publication DROP CONSTRAINT FK_BB29F5E638B217A7');
        $this->addSql('ALTER TABLE contributor_project DROP CONSTRAINT FK_EA7C10447A19A357');
        $this->addSql('ALTER TABLE contributor_project DROP CONSTRAINT FK_EA7C1044166D1F9C');
        $this->addSql('ALTER TABLE person_departement DROP CONSTRAINT FK_AC9D04EB217BBB47');
        $this->addSql('ALTER TABLE person_departement DROP CONSTRAINT FK_AC9D04EBCCF9E01E');
        $this->addSql('ALTER TABLE person_institution DROP CONSTRAINT FK_5774C76D217BBB47');
        $this->addSql('ALTER TABLE person_institution DROP CONSTRAINT FK_5774C76D10405986');
        $this->addSql('DROP TABLE contributor_publication');
        $this->addSql('DROP TABLE contributor_project');
        $this->addSql('DROP TABLE person_departement');
        $this->addSql('DROP TABLE person_institution');
        $this->addSql('ALTER TABLE person DROP CONSTRAINT FK_34DCD1762125FF59');
        $this->addSql('ALTER TABLE person ADD institution_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD departement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT fk_34dcd1762125ff59 FOREIGN KEY (student_profile_id) REFERENCES student_profile (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT fk_34dcd17610405986 FOREIGN KEY (institution_id) REFERENCES institution (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT fk_34dcd176ccf9e01e FOREIGN KEY (departement_id) REFERENCES departement (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_34dcd176ccf9e01e ON person (departement_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_34dcd17610405986 ON person (institution_id)');
        $this->addSql('ALTER TABLE publication ADD contributor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT fk_af3c67797a19a357 FOREIGN KEY (contributor_id) REFERENCES contributor (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_af3c67797a19a357 ON publication (contributor_id)');
        $this->addSql('ALTER TABLE student_profile DROP CONSTRAINT FK_6C611FF719E9AC5F');
        $this->addSql('ALTER TABLE student_profile DROP CONSTRAINT FK_6C611FF78D8661E8');
        $this->addSql('ALTER TABLE student_profile ADD CONSTRAINT fk_6c611ff719e9ac5f FOREIGN KEY (supervisor_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE student_profile ADD CONSTRAINT fk_6c611ff78d8661e8 FOREIGN KEY (co_supervisor_id) REFERENCES person (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
