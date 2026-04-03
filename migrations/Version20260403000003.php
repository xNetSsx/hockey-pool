<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260403000003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add payment settings columns to rule_set';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE rule_set ADD COLUMN payment_account_number VARCHAR(17) DEFAULT NULL");
        $this->addSql("ALTER TABLE rule_set ADD COLUMN payment_bank_code VARCHAR(4) DEFAULT NULL");
        $this->addSql("ALTER TABLE rule_set ADD COLUMN payment_amount DOUBLE PRECISION DEFAULT NULL");
        $this->addSql("ALTER TABLE rule_set ADD COLUMN payment_currency VARCHAR(3) NOT NULL DEFAULT 'CZK'");
        $this->addSql("ALTER TABLE rule_set ADD COLUMN payment_message VARCHAR(140) DEFAULT NULL");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE rule_set DROP COLUMN payment_account_number');
        $this->addSql('ALTER TABLE rule_set DROP COLUMN payment_bank_code');
        $this->addSql('ALTER TABLE rule_set DROP COLUMN payment_amount');
        $this->addSql('ALTER TABLE rule_set DROP COLUMN payment_currency');
        $this->addSql('ALTER TABLE rule_set DROP COLUMN payment_message');
    }
}
