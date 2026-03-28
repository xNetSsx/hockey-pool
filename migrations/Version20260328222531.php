<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260328222531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rule_set (id SERIAL NOT NULL, tournament_id INT NOT NULL, winner_base_points DOUBLE PRECISION DEFAULT \'1\' NOT NULL, wrong_opponent_bonus DOUBLE PRECISION DEFAULT \'0.25\' NOT NULL, exact_score_bonus DOUBLE PRECISION DEFAULT \'2\' NOT NULL, prizes JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_91D8622E33D1A3E7 ON rule_set (tournament_id)');
        $this->addSql('ALTER TABLE rule_set ADD CONSTRAINT FK_91D8622E33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE rule_set DROP CONSTRAINT FK_91D8622E33D1A3E7');
        $this->addSql('DROP TABLE rule_set');
    }
}
