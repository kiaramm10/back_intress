<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230410115917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE accumulated_vacation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, days_id INTEGER DEFAULT NULL, employee_id INTEGER DEFAULT NULL, CONSTRAINT FK_957667E83575FA99 FOREIGN KEY (days_id) REFERENCES holidays (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_957667E88C03F15C FOREIGN KEY (employee_id) REFERENCES personal (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_957667E83575FA99 ON accumulated_vacation (days_id)');
        $this->addSql('CREATE INDEX IDX_957667E88C03F15C ON accumulated_vacation (employee_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE accumulated_vacation');
    }
}
