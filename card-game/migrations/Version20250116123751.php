<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116123751 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, player_one_id INT DEFAULT NULL, player_two_id INT DEFAULT NULL, player_one_card_id INT DEFAULT NULL, player_two_card_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, result VARCHAR(255) NOT NULL, INDEX IDX_232B318C649A58CD (player_one_id), INDEX IDX_232B318CFC6BF02 (player_two_id), INDEX IDX_232B318C72E06E9B (player_one_card_id), INDEX IDX_232B318CB1A20DA8 (player_two_card_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C649A58CD FOREIGN KEY (player_one_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CFC6BF02 FOREIGN KEY (player_two_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C72E06E9B FOREIGN KEY (player_one_card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CB1A20DA8 FOREIGN KEY (player_two_card_id) REFERENCES card (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C649A58CD');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CFC6BF02');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C72E06E9B');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CB1A20DA8');
        $this->addSql('DROP TABLE game');
    }
}
