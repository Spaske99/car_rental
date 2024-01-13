<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240111191612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration for setting up relations between rent and user.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE rent ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_rent_user_id FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_rent_user_id ON rent (user_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE rent DROP CONSTRAINT FK_rent_user_id');
        $this->addSql('DROP INDEX IDX_rent_user_id');
        $this->addSql('ALTER TABLE rent DROP user_id');
    }
}
