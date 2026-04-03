<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260403000002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add isTiebreaker flag to match';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "match" ADD COLUMN is_tiebreaker BOOLEAN NOT NULL DEFAULT FALSE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "match" DROP COLUMN is_tiebreaker');
    }
}
