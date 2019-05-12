<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190512114847 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tutor DROP FOREIGN KEY FK_99074648A76ED395');
        $this->addSql('DROP INDEX UNIQ_99074648A76ED395 ON tutor');
        $this->addSql('ALTER TABLE tutor CHANGE user_id user_relation_id INT NOT NULL');
        $this->addSql('ALTER TABLE tutor ADD CONSTRAINT FK_990746489B4D58CE FOREIGN KEY (user_relation_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_990746489B4D58CE ON tutor (user_relation_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tutor DROP FOREIGN KEY FK_990746489B4D58CE');
        $this->addSql('DROP INDEX UNIQ_990746489B4D58CE ON tutor');
        $this->addSql('ALTER TABLE tutor CHANGE user_relation_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE tutor ADD CONSTRAINT FK_99074648A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_99074648A76ED395 ON tutor (user_id)');
    }
}
