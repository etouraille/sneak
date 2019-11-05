<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191102101910 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE size_price CHANGE mapping_id mapping_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mapping ADD current INT NOT NULL');
        $this->addSql('ALTER TABLE proxy CHANGE port port INT DEFAULT NULL, CHANGE secure secure TINYINT(1) DEFAULT NULL, CHANGE login login VARCHAR(255) DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE down down TINYINT(1) DEFAULT NULL, CHANGE blacklisted blacklisted TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mapping DROP current');
        $this->addSql('ALTER TABLE proxy CHANGE port port INT DEFAULT NULL, CHANGE secure secure TINYINT(1) DEFAULT \'NULL\', CHANGE login login VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password password VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE down down TINYINT(1) DEFAULT \'NULL\', CHANGE blacklisted blacklisted TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE size_price CHANGE mapping_id mapping_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
