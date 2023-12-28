<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231228161433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration for user table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "user" (
            id SERIAL PRIMARY KEY,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(100) NOT NULL,
            created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS "user"');
    }
}
