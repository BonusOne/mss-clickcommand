<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210611084723 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX index_liwocha ON redirect_data');
        $this->addSql('ALTER TABLE redirect_data CHANGE name name VARCHAR(400) NOT NULL, CHANGE id_liwocha_campaign id_sataku_campaign BIGINT UNSIGNED DEFAULT 0');
        $this->addSql('CREATE INDEX index_sataku ON redirect_data (id_sataku_campaign) USING BTREE');
        $this->addSql('CREATE INDEX index_search_redirect ON redirect_data (id, deleted, name, lp, id_smart_campaign, id_trackly_campaign, id_sataku_campaign) USING BTREE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX index_sataku ON redirect_data');
        $this->addSql('DROP INDEX index_search_redirect ON redirect_data');
        $this->addSql('ALTER TABLE redirect_data CHANGE name name VARCHAR(800) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE id_sataku_campaign id_liwocha_campaign BIGINT UNSIGNED DEFAULT 0');
        $this->addSql('CREATE INDEX index_liwocha ON redirect_data (id_liwocha_campaign)');
    }
}
