<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191113152439 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer ADD next_script_id INT UNSIGNED DEFAULT NULL');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25E64ACD63 FOREIGN KEY (next_script_id) REFERENCES script (id)');
        $this->addSql('CREATE INDEX IDX_DADD4A25E64ACD63 ON answer (next_script_id)');
        $this->addSql('ALTER TABLE script ADD is_start TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_finish TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25E64ACD63');
        $this->addSql('DROP INDEX IDX_DADD4A25E64ACD63 ON answer');
        $this->addSql('ALTER TABLE answer DROP next_script_id');
        $this->addSql('ALTER TABLE script DROP is_start, DROP is_finish');
    }
}
