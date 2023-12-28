<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231228185958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration for car table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE car (
            id SERIAL PRIMARY KEY, 
        brand VARCHAR(50) NOT NULL, 
        model VARCHAR(50) NOT NULL, 
        daily_price DOUBLE PRECISION NOT NULL, 
        description VARCHAR(255) NOT NULL, 
        image BYTEA NOT NULL, 
        created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, 
        updated TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS car');
    }
}
