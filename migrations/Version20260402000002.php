<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260402000002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add category column to point_entry for structured PointCategory enum';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE point_entry ADD category VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE point_entry DROP category');
    }
}
