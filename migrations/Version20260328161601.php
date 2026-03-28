<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260328161601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "match" (id SERIAL NOT NULL, tournament_id INT NOT NULL, home_team_id INT NOT NULL, away_team_id INT NOT NULL, phase VARCHAR(20) NOT NULL, played_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, home_score INT DEFAULT NULL, away_score INT DEFAULT NULL, is_finished BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_match_tournament ON "match" (tournament_id)');
        $this->addSql('CREATE INDEX idx_match_home_team ON "match" (home_team_id)');
        $this->addSql('CREATE INDEX idx_match_away_team ON "match" (away_team_id)');
        $this->addSql('CREATE INDEX idx_match_played_at ON "match" (played_at)');
        $this->addSql('CREATE TABLE point_entry (id SERIAL NOT NULL, user_id INT NOT NULL, tournament_id INT NOT NULL, game_id INT DEFAULT NULL, special_bet_type VARCHAR(30) DEFAULT NULL, points DOUBLE PRECISION NOT NULL, reason VARCHAR(255) NOT NULL, calculated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_point_entry_user ON point_entry (user_id)');
        $this->addSql('CREATE INDEX idx_point_entry_tournament ON point_entry (tournament_id)');
        $this->addSql('CREATE INDEX idx_point_entry_game ON point_entry (game_id)');
        $this->addSql('COMMENT ON COLUMN point_entry.calculated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE prediction (id SERIAL NOT NULL, user_id INT NOT NULL, game_id INT NOT NULL, home_score INT NOT NULL, away_score INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_prediction_user ON prediction (user_id)');
        $this->addSql('CREATE INDEX idx_prediction_game ON prediction (game_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_prediction_user_game ON prediction (user_id, game_id)');
        $this->addSql('COMMENT ON COLUMN prediction.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE special_bet (id SERIAL NOT NULL, user_id INT NOT NULL, tournament_id INT NOT NULL, team_value_id INT DEFAULT NULL, type VARCHAR(30) NOT NULL, string_value VARCHAR(255) DEFAULT NULL, int_value INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A40506CD416267DC ON special_bet (team_value_id)');
        $this->addSql('CREATE INDEX idx_special_bet_user ON special_bet (user_id)');
        $this->addSql('CREATE INDEX idx_special_bet_tournament ON special_bet (tournament_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_special_bet_user_tournament_type ON special_bet (user_id, tournament_id, type)');
        $this->addSql('CREATE TABLE team (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(3) NOT NULL, flag_emoji VARCHAR(16) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C4E0A61F77153098 ON team (code)');
        $this->addSql('CREATE TABLE tournament (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, year INT NOT NULL, slug VARCHAR(255) NOT NULL, status VARCHAR(20) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BD5FB8D9989D9B62 ON tournament (slug)');
        $this->addSql('COMMENT ON COLUMN tournament.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, username VARCHAR(180) NOT NULL, email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, is_admin BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE "match" ADD CONSTRAINT FK_7A5BC50533D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "match" ADD CONSTRAINT FK_7A5BC5059C4C13F6 FOREIGN KEY (home_team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "match" ADD CONSTRAINT FK_7A5BC50545185D02 FOREIGN KEY (away_team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE point_entry ADD CONSTRAINT FK_88141DE3A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE point_entry ADD CONSTRAINT FK_88141DE333D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE point_entry ADD CONSTRAINT FK_88141DE3E48FD905 FOREIGN KEY (game_id) REFERENCES "match" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prediction ADD CONSTRAINT FK_36396FC8A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE prediction ADD CONSTRAINT FK_36396FC8E48FD905 FOREIGN KEY (game_id) REFERENCES "match" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE special_bet ADD CONSTRAINT FK_A40506CDA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE special_bet ADD CONSTRAINT FK_A40506CD33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE special_bet ADD CONSTRAINT FK_A40506CD416267DC FOREIGN KEY (team_value_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "match" DROP CONSTRAINT FK_7A5BC50533D1A3E7');
        $this->addSql('ALTER TABLE "match" DROP CONSTRAINT FK_7A5BC5059C4C13F6');
        $this->addSql('ALTER TABLE "match" DROP CONSTRAINT FK_7A5BC50545185D02');
        $this->addSql('ALTER TABLE point_entry DROP CONSTRAINT FK_88141DE3A76ED395');
        $this->addSql('ALTER TABLE point_entry DROP CONSTRAINT FK_88141DE333D1A3E7');
        $this->addSql('ALTER TABLE point_entry DROP CONSTRAINT FK_88141DE3E48FD905');
        $this->addSql('ALTER TABLE prediction DROP CONSTRAINT FK_36396FC8A76ED395');
        $this->addSql('ALTER TABLE prediction DROP CONSTRAINT FK_36396FC8E48FD905');
        $this->addSql('ALTER TABLE special_bet DROP CONSTRAINT FK_A40506CDA76ED395');
        $this->addSql('ALTER TABLE special_bet DROP CONSTRAINT FK_A40506CD33D1A3E7');
        $this->addSql('ALTER TABLE special_bet DROP CONSTRAINT FK_A40506CD416267DC');
        $this->addSql('DROP TABLE "match"');
        $this->addSql('DROP TABLE point_entry');
        $this->addSql('DROP TABLE prediction');
        $this->addSql('DROP TABLE special_bet');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE tournament');
        $this->addSql('DROP TABLE "user"');
    }
}
