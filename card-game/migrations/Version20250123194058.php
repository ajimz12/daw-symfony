<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250123194058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game ADD player_one_second_card_id INT DEFAULT NULL, ADD player_two_second_card_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318CC0514853 FOREIGN KEY (player_one_second_card_id) REFERENCES card (id)');
        $this->addSql('ALTER TABLE game ADD CONSTRAINT FK_232B318C9171C0A9 FOREIGN KEY (player_two_second_card_id) REFERENCES card (id)');
        $this->addSql('CREATE INDEX IDX_232B318CC0514853 ON game (player_one_second_card_id)');
        $this->addSql('CREATE INDEX IDX_232B318C9171C0A9 ON game (player_two_second_card_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318CC0514853');
        $this->addSql('ALTER TABLE game DROP FOREIGN KEY FK_232B318C9171C0A9');
        $this->addSql('DROP INDEX IDX_232B318CC0514853 ON game');
        $this->addSql('DROP INDEX IDX_232B318C9171C0A9 ON game');
        $this->addSql('ALTER TABLE game DROP player_one_second_card_id, DROP player_two_second_card_id');
    }
}
