<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260422000001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add ON DELETE CASCADE to prediction.game_id foreign key';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE prediction DROP CONSTRAINT FK_36396FC8E48FD905');
        $this->addSql('ALTER TABLE prediction ADD CONSTRAINT FK_36396FC8E48FD905 FOREIGN KEY (game_id) REFERENCES "match" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE prediction DROP CONSTRAINT FK_36396FC8E48FD905');
        $this->addSql('ALTER TABLE prediction ADD CONSTRAINT FK_36396FC8E48FD905 FOREIGN KEY (game_id) REFERENCES "match" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
