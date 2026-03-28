<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260328163522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tournament_result (id SERIAL NOT NULL, tournament_id INT NOT NULL, gold_team_id INT NOT NULL, silver_team_id INT NOT NULL, bronze_team_id INT NOT NULL, best_czech_players JSON NOT NULL, czech_total_goals INT NOT NULL, regulation_draws INT NOT NULL, gudas_penalty_minutes INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_77C03F4333D1A3E7 ON tournament_result (tournament_id)');
        $this->addSql('CREATE INDEX IDX_77C03F431661F3FA ON tournament_result (gold_team_id)');
        $this->addSql('CREATE INDEX IDX_77C03F432481440B ON tournament_result (silver_team_id)');
        $this->addSql('CREATE INDEX IDX_77C03F43F15D82CB ON tournament_result (bronze_team_id)');
        $this->addSql('ALTER TABLE tournament_result ADD CONSTRAINT FK_77C03F4333D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournament_result ADD CONSTRAINT FK_77C03F431661F3FA FOREIGN KEY (gold_team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournament_result ADD CONSTRAINT FK_77C03F432481440B FOREIGN KEY (silver_team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tournament_result ADD CONSTRAINT FK_77C03F43F15D82CB FOREIGN KEY (bronze_team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE tournament_result DROP CONSTRAINT FK_77C03F4333D1A3E7');
        $this->addSql('ALTER TABLE tournament_result DROP CONSTRAINT FK_77C03F431661F3FA');
        $this->addSql('ALTER TABLE tournament_result DROP CONSTRAINT FK_77C03F432481440B');
        $this->addSql('ALTER TABLE tournament_result DROP CONSTRAINT FK_77C03F43F15D82CB');
        $this->addSql('DROP TABLE tournament_result');
    }
}
