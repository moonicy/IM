<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191214131543 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE laptop_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE employee_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE status (id INT NOT NULL, employee_id INT NOT NULL, laptop_id INT NOT NULL, status VARCHAR(255) NOT NULL, date_start TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_end TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7B00651C8C03F15C ON status (employee_id)');
        $this->addSql('CREATE INDEX IDX_7B00651CD59905E5 ON status (laptop_id)');
        $this->addSql('CREATE TABLE laptop (id INT NOT NULL, number VARCHAR(255) NOT NULL, firm VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, date_buy TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, interval VARCHAR(255) DEFAULT NULL, number_cores INT NOT NULL, memory INT NOT NULL, disk INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN laptop.interval IS \'(DC2Type:dateinterval)\'');
        $this->addSql('CREATE TABLE employee (id INT NOT NULL, fio VARCHAR(255) NOT NULL, position VARCHAR(255) NOT NULL, role SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE status ADD CONSTRAINT FK_7B00651C8C03F15C FOREIGN KEY (employee_id) REFERENCES employee (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE status ADD CONSTRAINT FK_7B00651CD59905E5 FOREIGN KEY (laptop_id) REFERENCES laptop (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE status DROP CONSTRAINT FK_7B00651CD59905E5');
        $this->addSql('ALTER TABLE status DROP CONSTRAINT FK_7B00651C8C03F15C');
        $this->addSql('DROP SEQUENCE status_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE laptop_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE employee_id_seq CASCADE');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE laptop');
        $this->addSql('DROP TABLE employee');
    }
}
