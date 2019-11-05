<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191031094814 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE proxy');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE sentence');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE proxy (id INT AUTO_INCREMENT NOT NULL, host VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, port INT DEFAULT NULL, secure TINYINT(1) DEFAULT \'NULL\', login VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, password VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, down TINYINT(1) DEFAULT \'NULL\', blacklisted TINYINT(1) DEFAULT \'NULL\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, word INT NOT NULL, url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, target LONGTEXT DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:object)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sentence (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(600) NOT NULL COLLATE utf8mb4_unicode_ci, length INT DEFAULT NULL, url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, article_rank INT NOT NULL, used INT NOT NULL, count INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE user');
    }
}
