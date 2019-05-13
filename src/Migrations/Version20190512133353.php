<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190512133353 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tutor (id INT AUTO_INCREMENT NOT NULL, user_relation_id INT NOT NULL, postcode VARCHAR(30) DEFAULT NULL, UNIQUE INDEX UNIQ_990746489B4D58CE (user_relation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tutor ADD CONSTRAINT FK_990746489B4D58CE FOREIGN KEY (user_relation_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD tutor_id INT DEFAULT NULL, ADD recommended_content INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649208F64F1 FOREIGN KEY (tutor_id) REFERENCES tutor (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649208F64F1 ON user (tutor_id)');
        $this->addSql('ALTER TABLE content ADD question VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649208F64F1');
        $this->addSql('DROP TABLE tutor');
        $this->addSql('ALTER TABLE content DROP question');
        $this->addSql('DROP INDEX IDX_8D93D649208F64F1 ON user');
        $this->addSql('ALTER TABLE user DROP tutor_id, DROP recommended_content');
    }
}
