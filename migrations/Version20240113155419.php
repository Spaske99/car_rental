<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240113155419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migration for setting up relations between rent and car.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE rent ADD car_id INT NOT NULL');
        $this->addSql('ALTER TABLE rent ADD CONSTRAINT FK_rent_car_id FOREIGN KEY (car_id) REFERENCES car (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_rent_car_id ON rent (car_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE rent DROP CONSTRAINT FK_rent_car_id');
        $this->addSql('DROP INDEX IDX_rent_car_id');
        $this->addSql('ALTER TABLE rent DROP car_id');
    }
}
