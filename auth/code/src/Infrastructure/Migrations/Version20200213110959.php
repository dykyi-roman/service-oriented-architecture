<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200213110959 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE DATABASE IF NOT EXISTS db');
    }

    public function down(Schema $schema): void
    {
        // TODO: Implement down() method.
    }
}
