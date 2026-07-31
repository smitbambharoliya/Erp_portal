<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260729120236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE announcement (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, created_by VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) DEFAULT NULL, file_path VARCHAR(255) NOT NULL, file_type VARCHAR(255) NOT NULL, uploaded_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE holiday (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, holiday_date VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, is_read TINYINT NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, priority VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, deadline VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('DROP TABLE tasks');
        $this->addSql('ALTER TABLE employee ADD task_id INT DEFAULT NULL, ADD document_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A18DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('CREATE INDEX IDX_5D9F75A18DB60186 ON employee (task_id)');
        $this->addSql('CREATE INDEX IDX_5D9F75A1C33F7837 ON employee (document_id)');
        $this->addSql('ALTER TABLE project ADD task_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('CREATE INDEX IDX_2FB3D0EE8DB60186 ON project (task_id)');
        $this->addSql('ALTER TABLE user ADD notification_id INT DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649EF1A9D84 ON user (notification_id)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tasks (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE announcement');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE holiday');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE task');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A18DB60186');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1C33F7837');
        $this->addSql('DROP INDEX IDX_5D9F75A18DB60186 ON employee');
        $this->addSql('DROP INDEX IDX_5D9F75A1C33F7837 ON employee');
        $this->addSql('ALTER TABLE employee DROP task_id, DROP document_id');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE8DB60186');
        $this->addSql('DROP INDEX IDX_2FB3D0EE8DB60186 ON project');
        $this->addSql('ALTER TABLE project DROP task_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649EF1A9D84');
        $this->addSql('DROP INDEX IDX_8D93D649EF1A9D84 ON user');
        $this->addSql('ALTER TABLE user DROP notification_id, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
