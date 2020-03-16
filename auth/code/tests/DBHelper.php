<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DBHelper extends WebTestCase
{
    public static function clearTable(string $tableName): void
    {
        $container = self::$kernel->getContainer();
        $entityManager = $container->get('doctrine.orm.default_entity_manager');
        $connection = $entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeUpdate($platform->getTruncateTableSQL($tableName, true /* whether to cascade */));
    }
}