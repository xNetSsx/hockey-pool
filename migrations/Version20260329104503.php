<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260329104503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tournament_participant (id SERIAL NOT NULL, user_id INT NOT NULL, tournament_id INT NOT NULL, paid BOOLEAN NOT NULL, joined_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5C4BB35BA76ED395 ON tournament_participant (user_id)');
        $this->addSql('CREATE INDEX IDX_5C4BB35B33D1A3E7 ON tournament_participant (tournament_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_participant_user_tournament ON tournament_participant (user_id, tournament_id)');
        $this->addSql('COMMENT ON COLUMN tournament_participant.joined_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE tournament_participant ADD CONSTRAINT FK_5C4BB35BA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournament_participant ADD CONSTRAINT FK_5C4BB35B33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tournament_participant DROP CONSTRAINT FK_5C4BB35BA76ED395');
        $this->addSql('ALTER TABLE tournament_participant DROP CONSTRAINT FK_5C4BB35B33D1A3E7');
        $this->addSql('DROP TABLE tournament_participant');
    }
}
