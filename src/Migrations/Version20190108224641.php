<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190108224641 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, trick_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, alt VARCHAR(255) NOT NULL, INDEX IDX_C53D045FB281BE2E (trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, trick_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_7CC7DA2CB281BE2E (trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE trick ADD group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91EFE54D947 ON trick (group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EFE54D947');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE video');
        $this->addSql('DROP INDEX IDX_D8F0A91EFE54D947 ON trick');
        $this->addSql('ALTER TABLE trick DROP group_id');
    }
}
