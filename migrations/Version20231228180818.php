<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231228180818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration for rent table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE rent (
            id SERIAL PRIMARY KEY,
            rented_from TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            rented_until TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            approved BOOLEAN NOT NULL
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS rent');
    }
}
