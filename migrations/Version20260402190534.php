<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260402190534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change Game.playedAt and Prediction.updatedAt to datetime_immutable; add is_medal_rule to special_bet_rule; rename tournament_id index on participant';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "match" ALTER played_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN "match".played_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE prediction ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN prediction.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE special_bet_rule ADD is_medal_rule BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER INDEX idx_5c4bb35b33d1a3e7 RENAME TO idx_participant_tournament');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER INDEX idx_participant_tournament RENAME TO idx_5c4bb35b33d1a3e7');
        $this->addSql('ALTER TABLE special_bet_rule DROP is_medal_rule');
        $this->addSql('ALTER TABLE "match" ALTER played_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN "match".played_at IS NULL');
        $this->addSql('ALTER TABLE prediction ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN prediction.updated_at IS NULL');
    }
}
