<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260328190209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE tournament_result_id_seq CASCADE');
        $this->addSql('CREATE TABLE special_bet_rule (id SERIAL NOT NULL, tournament_id INT NOT NULL, actual_team_value_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, value_type VARCHAR(20) NOT NULL, scoring_type VARCHAR(20) NOT NULL, points DOUBLE PRECISION NOT NULL, sort_order INT NOT NULL, actual_string_value VARCHAR(255) DEFAULT NULL, actual_int_value INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E3B3146770DD83D6 ON special_bet_rule (actual_team_value_id)');
        $this->addSql('CREATE INDEX idx_special_bet_rule_tournament ON special_bet_rule (tournament_id)');
        $this->addSql('ALTER TABLE special_bet_rule ADD CONSTRAINT FK_E3B3146733D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE special_bet_rule ADD CONSTRAINT FK_E3B3146770DD83D6 FOREIGN KEY (actual_team_value_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournament_result DROP CONSTRAINT fk_77c03f4333d1a3e7');
        $this->addSql('ALTER TABLE tournament_result DROP CONSTRAINT fk_77c03f431661f3fa');
        $this->addSql('ALTER TABLE tournament_result DROP CONSTRAINT fk_77c03f432481440b');
        $this->addSql('ALTER TABLE tournament_result DROP CONSTRAINT fk_77c03f43f15d82cb');
        $this->addSql('DROP TABLE tournament_result');
        $this->addSql('ALTER TABLE point_entry ADD special_bet_rule_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE point_entry DROP special_bet_type');
        $this->addSql('ALTER TABLE point_entry ADD CONSTRAINT FK_88141DE3571F2D15 FOREIGN KEY (special_bet_rule_id) REFERENCES special_bet_rule (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_88141DE3571F2D15 ON point_entry (special_bet_rule_id)');
        $this->addSql('ALTER TABLE special_bet DROP CONSTRAINT fk_a40506cd33d1a3e7');
        $this->addSql('DROP INDEX uniq_special_bet_user_tournament_type');
        $this->addSql('DROP INDEX idx_special_bet_tournament');
        $this->addSql('ALTER TABLE special_bet DROP type');
        $this->addSql('ALTER TABLE special_bet RENAME COLUMN tournament_id TO rule_id');
        $this->addSql('ALTER TABLE special_bet ADD CONSTRAINT FK_A40506CD744E0351 FOREIGN KEY (rule_id) REFERENCES special_bet_rule (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_special_bet_rule ON special_bet (rule_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_special_bet_user_rule ON special_bet (user_id, rule_id)');
        $this->addSql('ALTER TABLE "user" DROP is_admin');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE point_entry DROP CONSTRAINT FK_88141DE3571F2D15');
        $this->addSql('ALTER TABLE special_bet DROP CONSTRAINT FK_A40506CD744E0351');
        $this->addSql('CREATE SEQUENCE tournament_result_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tournament_result (id SERIAL NOT NULL, tournament_id INT NOT NULL, gold_team_id INT NOT NULL, silver_team_id INT NOT NULL, bronze_team_id INT NOT NULL, best_czech_players JSON NOT NULL, czech_total_goals INT NOT NULL, regulation_draws INT NOT NULL, gudas_penalty_minutes INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_77c03f43f15d82cb ON tournament_result (bronze_team_id)');
        $this->addSql('CREATE INDEX idx_77c03f432481440b ON tournament_result (silver_team_id)');
        $this->addSql('CREATE INDEX idx_77c03f431661f3fa ON tournament_result (gold_team_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_77c03f4333d1a3e7 ON tournament_result (tournament_id)');
        $this->addSql('ALTER TABLE tournament_result ADD CONSTRAINT fk_77c03f4333d1a3e7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournament_result ADD CONSTRAINT fk_77c03f431661f3fa FOREIGN KEY (gold_team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournament_result ADD CONSTRAINT fk_77c03f432481440b FOREIGN KEY (silver_team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournament_result ADD CONSTRAINT fk_77c03f43f15d82cb FOREIGN KEY (bronze_team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE special_bet_rule DROP CONSTRAINT FK_E3B3146733D1A3E7');
        $this->addSql('ALTER TABLE special_bet_rule DROP CONSTRAINT FK_E3B3146770DD83D6');
        $this->addSql('DROP TABLE special_bet_rule');
        $this->addSql('ALTER TABLE "user" ADD is_admin BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('DROP INDEX idx_special_bet_rule');
        $this->addSql('DROP INDEX uniq_special_bet_user_rule');
        $this->addSql('ALTER TABLE special_bet ADD type VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE special_bet RENAME COLUMN rule_id TO tournament_id');
        $this->addSql('ALTER TABLE special_bet ADD CONSTRAINT fk_a40506cd33d1a3e7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_special_bet_user_tournament_type ON special_bet (user_id, tournament_id, type)');
        $this->addSql('CREATE INDEX idx_special_bet_tournament ON special_bet (tournament_id)');
        $this->addSql('DROP INDEX IDX_88141DE3571F2D15');
        $this->addSql('ALTER TABLE point_entry ADD special_bet_type VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE point_entry DROP special_bet_rule_id');
    }
}
