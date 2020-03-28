<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200313110958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(<<<'TAG'
            CREATE TABLE refresh_tokens (
            id INT AUTO_INCREMENT NOT NULL, 
            refresh_token VARCHAR(128) NOT NULL, 
            username VARCHAR(255) NOT NULL, 
            valid DATETIME NOT NULL, 
            UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), 
            PRIMARY KEY(id)) 
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            TAG
        );

        $this->addSql('DROP TABLE test');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(<<<'TAG'
            CREATE TABLE test (
            id INT NOT NULL) 
            DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = '' 
            TAG
        );
        $this->addSql('DROP TABLE refresh_tokens');
    }
}
