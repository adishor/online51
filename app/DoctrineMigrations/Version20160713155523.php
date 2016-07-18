<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160713155523 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('INSERT INTO formular SET name="Decizie Comisie Cercetare Accidente", credit_value=0');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        $this->addSql('DELETE FROM formular WHERE name="Decizie Comisie Cercetare Accidente"');
    }
}
