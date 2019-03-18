<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190318215133 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE token DROP FOREIGN KEY FK_5F37A13BA76ED395');
        $this->addSql('DROP INDEX UNIQ_5F37A13BA76ED395 ON token');
        $this->addSql('ALTER TABLE token DROP user_id');
        $this->addSql('ALTER TABLE user ADD token_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64941DEE7B9 FOREIGN KEY (token_id) REFERENCES token (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64941DEE7B9 ON user (token_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE token ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE token ADD CONSTRAINT FK_5F37A13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F37A13BA76ED395 ON token (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64941DEE7B9');
        $this->addSql('DROP INDEX UNIQ_8D93D64941DEE7B9 ON user');
        $this->addSql('ALTER TABLE user DROP token_id');
    }
}
