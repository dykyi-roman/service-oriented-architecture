<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

final class Version20200312101807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if ($schema->hasTable('users')) {
            return;
        }

        $users = $schema->createTable('users');
        $users->addColumn('id', Types::STRING, ['length' => 36]);
        $users->addColumn('full_name', Types::STRING);
        $users->addColumn('phone', Types::STRING, ['length' => 50]);
        $users->addColumn('password', Types::STRING, ['length' => 500]);
        $users->addColumn('email', Types::STRING, ['length' => 80]);
        $users->addColumn('is_active', Types::BOOLEAN);

        $users->setPrimaryKey(['id']);
        $users->addUniqueIndex(['email']);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('users');
    }
}
