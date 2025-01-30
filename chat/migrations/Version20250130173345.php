<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250130173345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat_group (id INT AUTO_INCREMENT NOT NULL, creador_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_6CBDA95E62F40C3D (creador_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_group_user (chat_group_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_6338C89FCA760E77 (chat_group_id), INDEX IDX_6338C89FA76ED395 (user_id), PRIMARY KEY(chat_group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat_group ADD CONSTRAINT FK_6CBDA95E62F40C3D FOREIGN KEY (creador_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE chat_group_user ADD CONSTRAINT FK_6338C89FCA760E77 FOREIGN KEY (chat_group_id) REFERENCES chat_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_group_user ADD CONSTRAINT FK_6338C89FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD chat_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCA760E77 FOREIGN KEY (chat_group_id) REFERENCES chat_group (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FCA760E77 ON message (chat_group_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCA760E77');
        $this->addSql('ALTER TABLE chat_group DROP FOREIGN KEY FK_6CBDA95E62F40C3D');
        $this->addSql('ALTER TABLE chat_group_user DROP FOREIGN KEY FK_6338C89FCA760E77');
        $this->addSql('ALTER TABLE chat_group_user DROP FOREIGN KEY FK_6338C89FA76ED395');
        $this->addSql('DROP TABLE chat_group');
        $this->addSql('DROP TABLE chat_group_user');
        $this->addSql('DROP INDEX IDX_B6BD307FCA760E77 ON message');
        $this->addSql('ALTER TABLE message DROP chat_group_id');
    }
}
