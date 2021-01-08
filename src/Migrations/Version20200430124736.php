<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200430124736 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX index_browser ON click_statistics');
        $this->addSql('DROP INDEX index_device ON click_statistics');
        $this->addSql('DROP INDEX index_operating_system ON click_statistics');
        $this->addSql('ALTER TABLE click_statistics CHANGE timestamp timestamp DATETIME DEFAULT NULL, CHANGE date date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('CREATE INDEX index_browser ON click_statistics (browser) USING BTREE');
        $this->addSql('CREATE INDEX index_device ON click_statistics (device) USING BTREE');
        $this->addSql('CREATE INDEX index_operating_system ON click_statistics (operating_system) USING BTREE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX index_browser ON click_statistics');
        $this->addSql('DROP INDEX index_operating_system ON click_statistics');
        $this->addSql('DROP INDEX index_device ON click_statistics');
        $this->addSql('ALTER TABLE click_statistics CHANGE timestamp timestamp DATETIME DEFAULT CURRENT_TIMESTAMP, CHANGE date date DATETIME NOT NULL');
        $this->addSql('CREATE INDEX index_browser ON click_statistics (browser(191)) USING BTREE');
        $this->addSql('CREATE INDEX index_operating_system ON click_statistics (operating_system(191)) USING BTREE');
        $this->addSql('CREATE INDEX index_device ON click_statistics (device(191)) USING BTREE');
    }
}
