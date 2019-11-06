<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191031111240 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE game_constraint (id INT UNSIGNED AUTO_INCREMENT NOT NULL, game_id INT UNSIGNED DEFAULT NULL, characteristic_id INT UNSIGNED DEFAULT NULL, type INT NOT NULL, value_string VARCHAR(255) DEFAULT NULL, value_int INT DEFAULT NULL, INDEX IDX_D796491FE48FD905 (game_id), INDEX IDX_D796491FDEE9D12B (characteristic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_context (id INT UNSIGNED AUTO_INCREMENT NOT NULL, user_id INT UNSIGNED DEFAULT NULL, game_id INT UNSIGNED DEFAULT NULL, context VARCHAR(1024) NOT NULL, INDEX IDX_779FFA51A76ED395 (user_id), INDEX IDX_779FFA51E48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game_constraint ADD CONSTRAINT FK_D796491FE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE game_constraint ADD CONSTRAINT FK_D796491FDEE9D12B FOREIGN KEY (characteristic_id) REFERENCES characteristic (id)');
        $this->addSql('ALTER TABLE game_context ADD CONSTRAINT FK_779FFA51A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game_context ADD CONSTRAINT FK_779FFA51E48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE game_constraint');
        $this->addSql('DROP TABLE game_context');
    }
}
