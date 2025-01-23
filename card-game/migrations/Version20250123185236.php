<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250123185236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE player_one_cards DROP FOREIGN KEY FK_E608ABEB4ACC9A20');
        $this->addSql('ALTER TABLE player_one_cards DROP FOREIGN KEY FK_E608ABEBE48FD905');
        $this->addSql('ALTER TABLE player_two_cards DROP FOREIGN KEY FK_F1902094ACC9A20');
        $this->addSql('ALTER TABLE player_two_cards DROP FOREIGN KEY FK_F190209E48FD905');
        $this->addSql('DROP TABLE player_one_cards');
        $this->addSql('DROP TABLE player_two_cards');
        $this->addSql('ALTER TABLE game ADD player_one_card_id INT DEFAULT NULL, ADD player_two_card_id INT DEFAULT NULL, CHANGE result result VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C72E06E9B FOREIGN KEY (player_one_card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CB1A20DA8 FOREIGN KEY (player_two_card_id) REFERENCES card (id)');
        $this->addSql('CREATE INDEX IDX_232B318C72E06E9B ON game (player_one_card_id)');
        $this->addSql('CREATE INDEX IDX_232B318CB1A20DA8 ON game (player_two_card_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE player_one_cards (game_id INT NOT NULL, card_id INT NOT NULL, INDEX IDX_E608ABEBE48FD905 (game_id), INDEX IDX_E608ABEB4ACC9A20 (card_id), PRIMARY KEY(game_id, card_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE player_two_cards (game_id INT NOT NULL, card_id INT NOT NULL, INDEX IDX_F190209E48FD905 (game_id), INDEX IDX_F1902094ACC9A20 (card_id), PRIMARY KEY(game_id, card_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE player_one_cards ADD CONSTRAINT FK_E608ABEB4ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_one_cards ADD CONSTRAINT FK_E608ABEBE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_two_cards ADD CONSTRAINT FK_F1902094ACC9A20 FOREIGN KEY (card_id) REFERENCES card (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE player_two_cards ADD CONSTRAINT FK_F190209E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C72E06E9B');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CB1A20DA8');
        $this->addSql('DROP INDEX IDX_232B318C72E06E9B ON game');
        $this->addSql('DROP INDEX IDX_232B318CB1A20DA8 ON game');
        $this->addSql('ALTER TABLE game DROP player_one_card_id, DROP player_two_card_id, CHANGE result result VARCHAR(255) DEFAULT NULL');
    }
}
