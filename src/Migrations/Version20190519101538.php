<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190519101538 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, tutor_id INT DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, roles JSON NOT NULL, reset_token VARCHAR(255) DEFAULT NULL, age VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649208F64F1 (tutor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_content (user_id INT NOT NULL, content_id INT NOT NULL, INDEX IDX_A6C82EA3A76ED395 (user_id), INDEX IDX_A6C82EA384A0A3ED (content_id), PRIMARY KEY(user_id, content_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE content (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, cover_image VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, public TINYINT(1) NOT NULL, link VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, last_update_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, question VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participate_content (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, content_id INT DEFAULT NULL, result JSON NOT NULL, created_at DATETIME NOT NULL, completed_at DATETIME DEFAULT NULL, INDEX IDX_EFFFB8B2A76ED395 (user_id), INDEX IDX_EFFFB8B284A0A3ED (content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, content_id INT NOT NULL, url VARCHAR(255) NOT NULL, caption VARCHAR(255) NOT NULL, INDEX IDX_C53D045F84A0A3ED (content_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649208F64F1 FOREIGN KEY (tutor_id) REFERENCES tutor (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user_content ADD CONSTRAINT FK_A6C82EA3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_content ADD CONSTRAINT FK_A6C82EA384A0A3ED FOREIGN KEY (content_id) REFERENCES content (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participate_content ADD CONSTRAINT FK_EFFFB8B2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE participate_content ADD CONSTRAINT FK_EFFFB8B284A0A3ED FOREIGN KEY (content_id) REFERENCES content (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F84A0A3ED FOREIGN KEY (content_id) REFERENCES content (id)');
        $this->addSql('ALTER TABLE tutor ADD adeli VARCHAR(255) DEFAULT NULL, ADD plain_text_pass VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tutor ADD CONSTRAINT FK_990746489B4D58CE FOREIGN KEY (user_relation_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_content DROP FOREIGN KEY FK_A6C82EA3A76ED395');
        $this->addSql('ALTER TABLE participate_content DROP FOREIGN KEY FK_EFFFB8B2A76ED395');
        $this->addSql('ALTER TABLE tutor DROP FOREIGN KEY FK_990746489B4D58CE');
        $this->addSql('ALTER TABLE user_content DROP FOREIGN KEY FK_A6C82EA384A0A3ED');
        $this->addSql('ALTER TABLE participate_content DROP FOREIGN KEY FK_EFFFB8B284A0A3ED');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F84A0A3ED');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_content');
        $this->addSql('DROP TABLE content');
        $this->addSql('DROP TABLE participate_content');
        $this->addSql('DROP TABLE image');
        $this->addSql('ALTER TABLE tutor DROP adeli, DROP plain_text_pass');
    }
}
