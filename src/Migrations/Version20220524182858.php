<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220524182858 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE click_statistics ADD lp INT UNSIGNED NOT NULL AFTER id_smart_insertion');
        $this->addSql('CREATE INDEX id_smart_lp ON click_statistics (id_smart_insertion, lp, date) USING BTREE');
        $this->addSql('CREATE INDEX id_smart_lp ON redirect_data (id_smart_campaign, lp) USING BTREE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX id_smart_lp ON redirect_data');
        $this->addSql('DROP INDEX id_smart_lp ON click_statistics');
        $this->addSql('ALTER TABLE click_statistics DROP lp');
    }
}
