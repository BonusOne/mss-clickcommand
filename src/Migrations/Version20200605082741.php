<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200605082741 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        /*$this->addSql('DROP INDEX index_device ON click_statistics');
        $this->addSql('DROP INDEX index_operating_system ON click_statistics');
        $this->addSql('DROP INDEX index_browser ON click_statistics');
        $this->addSql('CREATE INDEX index_device ON click_statistics (device) USING BTREE');
        $this->addSql('CREATE INDEX index_operating_system ON click_statistics (operating_system) USING BTREE');
        $this->addSql('CREATE INDEX index_browser ON click_statistics (browser) USING BTREE');*/
        $this->addSql('ALTER TABLE redirect_data ADD using_smart INT UNSIGNED DEFAULT 0');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        /*$this->addSql('DROP INDEX index_browser ON click_statistics');
        $this->addSql('DROP INDEX index_operating_system ON click_statistics');
        $this->addSql('DROP INDEX index_device ON click_statistics');
        $this->addSql('CREATE INDEX index_browser ON click_statistics (browser(191)) USING BTREE');
        $this->addSql('CREATE INDEX index_operating_system ON click_statistics (operating_system(191)) USING BTREE');
        $this->addSql('CREATE INDEX index_device ON click_statistics (device(191)) USING BTREE');*/
        $this->addSql('ALTER TABLE redirect_data DROP using_smart');
    }
}
