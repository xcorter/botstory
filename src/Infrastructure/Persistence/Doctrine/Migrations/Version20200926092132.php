<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200926092132 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251CF5F25E');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');

        $this->addSql('DROP INDEX IDX_DADD4A251CF5F25E ON answer');
        $this->addSql('DROP INDEX IDX_DADD4A251E27F6BF ON answer');

        $this->addSql('ALTER TABLE question RENAME TO node');
        $this->addSql('ALTER TABLE answer RENAME COLUMN next_question_id TO next_node_id');
        $this->addSql('ALTER TABLE answer RENAME COLUMN question_id TO node_id');

        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A25460D9FD7 FOREIGN KEY (node_id) REFERENCES node (id)');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A258483B575 FOREIGN KEY (next_node_id) REFERENCES node (id)');
        $this->addSql('CREATE INDEX IDX_DADD4A25460D9FD7 ON answer (node_id)');
        $this->addSql('CREATE INDEX IDX_DADD4A258483B575 ON answer (next_node_id)');

        $this->addSql('ALTER TABLE node RENAME INDEX idx_b6f7494ee48fd905 TO IDX_857FE845E48FD905');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A25460D9FD7');
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A258483B575');

        $this->addSql('DROP INDEX IDX_DADD4A25460D9FD7 ON answer');
        $this->addSql('DROP INDEX IDX_DADD4A258483B575 ON answer');

        $this->addSql('ALTER TABLE node RENAME TO question');
        $this->addSql('ALTER TABLE answer RENAME COLUMN next_node_id TO next_question_id');
        $this->addSql('ALTER TABLE answer RENAME COLUMN node_id TO question_id');


        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251CF5F25E FOREIGN KEY (next_question_id) REFERENCES question (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_DADD4A251CF5F25E ON answer (next_question_id)');
        $this->addSql('CREATE INDEX IDX_DADD4A251E27F6BF ON answer (question_id)');

        $this->addSql('ALTER TABLE node RENAME INDEX idx_857fe845e48fd905 TO IDX_B6F7494EE48FD905');
    }
}
