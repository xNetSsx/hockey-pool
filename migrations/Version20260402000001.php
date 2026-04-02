<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260402000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add index on tournament.year for faster year-based lookups';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE INDEX idx_tournament_year ON tournament (year DESC)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX idx_tournament_year');
    }
}
