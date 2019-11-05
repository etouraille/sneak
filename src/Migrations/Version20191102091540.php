<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191102091540 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE size_price (id INT AUTO_INCREMENT NOT NULL, mapping_id INT DEFAULT NULL, size VARCHAR(255) NOT NULL, price VARCHAR(255) NOT NULL, INDEX IDX_1B18E0EEFABB77CC (mapping_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE backup (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL, data LONGBLOB NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mapping (id INT AUTO_INCREMENT NOT NULL, shopify_url VARCHAR(255) NOT NULL, stockx_url VARCHAR(255) NOT NULL, hash_old_price_and_size VARCHAR(255) NOT NULL, INDEX matches (shopify_url, stockx_url), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE size_price ADD CONSTRAINT FK_1B18E0EEFABB77CC FOREIGN KEY (mapping_id) REFERENCES mapping (id)');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE sentence');
        $this->addSql('ALTER TABLE proxy CHANGE port port INT DEFAULT NULL, CHANGE secure secure TINYINT(1) DEFAULT NULL, CHANGE login login VARCHAR(255) DEFAULT NULL, CHANGE password password VARCHAR(255) DEFAULT NULL, CHANGE down down TINYINT(1) DEFAULT NULL, CHANGE blacklisted blacklisted TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE size_price DROP FOREIGN KEY FK_1B18E0EEFABB77CC');
        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, word INT NOT NULL, url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, target LONGTEXT DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:object)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE sentence (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(600) NOT NULL COLLATE utf8mb4_unicode_ci, length INT DEFAULT NULL, url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, article_rank INT NOT NULL, used INT NOT NULL, count INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE size_price');
        $this->addSql('DROP TABLE backup');
        $this->addSql('DROP TABLE mapping');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE proxy CHANGE port port INT DEFAULT NULL, CHANGE secure secure TINYINT(1) DEFAULT \'NULL\', CHANGE login login VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE password password VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE down down TINYINT(1) DEFAULT \'NULL\', CHANGE blacklisted blacklisted TINYINT(1) DEFAULT \'NULL\'');
    }
}
