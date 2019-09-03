<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20190830193200
 * @package DoctrineMigrations
 */
final class Version20190830193200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        if ($this->connection->getDatabasePlatform()->getName() === 'mysql') {
            $this->addSql('CREATE TABLE horse (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, picture_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
            $this->addSql('CREATE TABLE user_api_token (uid VARCHAR(36) NOT NULL, name VARCHAR(128) NOT NULL, api_token VARCHAR(128) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7B42780F5E237E06 (name), UNIQUE INDEX UNIQ_7B42780F7BA2F5EB (api_token), PRIMARY KEY(uid)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        }

        if ($this->connection->getDatabasePlatform()->getName() === 'sqlite') {
            $this->addSql('CREATE TABLE horse (id VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, picture_url VARCHAR(255), PRIMARY KEY(id))');
            $this->addSql('CREATE TABLE user_api_token (uid VARCHAR(36) NOT NULL, name VARCHAR(128) NOT NULL, api_token VARCHAR(128) NOT NULL, roles LONGTEXT NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(uid))');
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE horse');
        $this->addSql('DROP TABLE user_api_token');
    }
}
