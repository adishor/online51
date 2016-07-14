<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160713180336 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO formular SET name="Decizie Organizare Activitate SSM", credit_value=0');
        $this->addSql('INSERT INTO formular SET name="Proces Verbal Sedinta CSSM", credit_value=0');
        $this->addSql('INSERT INTO formular SET name="Proces Verbal Control", credit_value=0');
        $this->addSql('INSERT INTO formular SET name="Permis De Lucru Cu Foc", credit_value=0');
        $this->addSql('INSERT INTO formular SET name="Decizie Personal Cu Atributii PSI", credit_value=0');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DELETE FROM formular WHERE name="Decizie Organizare Activitate SSM"');
        $this->addSql('DELETE FROM formular WHERE name="Proces Verbal Sedinta CSSM"');
        $this->addSql('DELETE FROM formular WHERE name="Proces Verbal Control"');
        $this->addSql('DELETE FROM formular WHERE name="Permis De Lucru Cu Foc"');
        $this->addSql('DELETE FROM formular WHERE name="Decizie Personal Cu Atributii PSI"');
    }
}
