<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200430123946 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE click_statistics (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, redirect_id BIGINT UNSIGNED NOT NULL, id_smart_insertion BIGINT UNSIGNED DEFAULT 0, id_company INT NOT NULL, timestamp DATETIME DEFAULT CURRENT_TIMESTAMP, ipv4 VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, referer VARCHAR(2000) DEFAULT NULL, browser VARCHAR(255) DEFAULT NULL, operating_system VARCHAR(255) DEFAULT NULL, device VARCHAR(255) DEFAULT NULL, rendering_engine VARCHAR(255) DEFAULT NULL, useragent VARCHAR(1500) DEFAULT NULL, INDEX index_id (id) USING BTREE, INDEX index_redirect_id (redirect_id) USING BTREE, INDEX index_smart (id_smart_insertion) USING BTREE, INDEX index_company (id_company) USING BTREE, INDEX index_timestamp (timestamp) USING BTREE, INDEX index_date (date) USING BTREE, INDEX index_browser (browser) USING BTREE, INDEX index_operating_system (operating_system) USING BTREE, INDEX index_device (device) USING BTREE, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE redirect_data (id BIGINT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(800) NOT NULL, lp INT NOT NULL, url VARCHAR(2000) NOT NULL, id_smart_campaign BIGINT UNSIGNED DEFAULT 0, id_trackly_campaign BIGINT UNSIGNED DEFAULT 0, id_liwocha_campaign BIGINT UNSIGNED DEFAULT 0, date DATETIME NOT NULL, INDEX index_id (id) USING BTREE, INDEX index_smart (id_smart_campaign) USING BTREE, INDEX index_trackly (id_trackly_campaign) USING BTREE, INDEX index_liwocha (id_liwocha_campaign) USING BTREE, INDEX index_date (date) USING BTREE, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE click_statistics');
        $this->addSql('DROP TABLE redirect_data');
    }
}
