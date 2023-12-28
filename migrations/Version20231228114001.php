<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20231228114001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration for roles table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE role (id SERIAL PRIMARY KEY, role_type VARCHAR(50) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS role');
    }
}
