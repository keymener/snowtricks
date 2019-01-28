<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190128195007 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EFE54D947');
        $this->addSql('CREATE TABLE trick_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('ALTER TABLE image ADD name VARCHAR(255) NOT NULL, ADD is_first TINYINT(1) DEFAULT NULL, DROP url, DROP alt');
        $this->addSql('DROP INDEX IDX_D8F0A91EFE54D947 ON trick');
        $this->addSql('ALTER TABLE trick ADD first_image_id INT DEFAULT NULL, CHANGE group_id trick_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E9B875DF8 FOREIGN KEY (trick_group_id) REFERENCES trick_group (id)');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E89BD1736 FOREIGN KEY (first_image_id) REFERENCES image (id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91E9B875DF8 ON trick (trick_group_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8F0A91E89BD1736 ON trick (first_image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E9B875DF8');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE trick_group');
        $this->addSql('ALTER TABLE image ADD alt VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP is_first, CHANGE name url VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E89BD1736');
        $this->addSql('DROP INDEX IDX_D8F0A91E9B875DF8 ON trick');
        $this->addSql('DROP INDEX UNIQ_D8F0A91E89BD1736 ON trick');
        $this->addSql('ALTER TABLE trick ADD group_id INT DEFAULT NULL, DROP trick_group_id, DROP first_image_id');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91EFE54D947 ON trick (group_id)');
    }
}
