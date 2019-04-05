<?php

declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190405182750 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE workshop_entry DROP FOREIGN KEY FK_C3A3CE6ABDAFD8C8');
        $this->addSql('CREATE TABLE oauth2_access_tokens (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D247A21B5F37A13B (token), INDEX IDX_D247A21B19EB6921 (client_id), INDEX IDX_D247A21BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth2_clients (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, random_id VARCHAR(255) NOT NULL, redirect_uris LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', secret VARCHAR(255) NOT NULL, allowed_grant_types LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_F9D02AE6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth2_refresh_tokens (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_D394478C5F37A13B (token), INDEX IDX_D394478C19EB6921 (client_id), INDEX IDX_D394478CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth2_auth_codes (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, redirect_uri LONGTEXT NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_A018A10D5F37A13B (token), INDEX IDX_A018A10D19EB6921 (client_id), INDEX IDX_A018A10DA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE UTF8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE oauth2_access_tokens ADD CONSTRAINT FK_D247A21B19EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_clients (id)');
        $this->addSql('ALTER TABLE oauth2_access_tokens ADD CONSTRAINT FK_D247A21BA76ED395 FOREIGN KEY (user_id) REFERENCES Registred_users (id)');
        $this->addSql('ALTER TABLE oauth2_clients ADD CONSTRAINT FK_F9D02AE6A76ED395 FOREIGN KEY (user_id) REFERENCES Registred_users (id)');
        $this->addSql('ALTER TABLE oauth2_refresh_tokens ADD CONSTRAINT FK_D394478C19EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_clients (id)');
        $this->addSql('ALTER TABLE oauth2_refresh_tokens ADD CONSTRAINT FK_D394478CA76ED395 FOREIGN KEY (user_id) REFERENCES Registred_users (id)');
        $this->addSql('ALTER TABLE oauth2_auth_codes ADD CONSTRAINT FK_A018A10D19EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_clients (id)');
        $this->addSql('ALTER TABLE oauth2_auth_codes ADD CONSTRAINT FK_A018A10DA76ED395 FOREIGN KEY (user_id) REFERENCES Registred_users (id)');
        $this->addSql('DROP TABLE book_review');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE users_workshop');
        $this->addSql('DROP TABLE workshop_entry');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE oauth2_access_tokens DROP FOREIGN KEY FK_D247A21B19EB6921');
        $this->addSql('ALTER TABLE oauth2_refresh_tokens DROP FOREIGN KEY FK_D394478C19EB6921');
        $this->addSql('ALTER TABLE oauth2_auth_codes DROP FOREIGN KEY FK_A018A10D19EB6921');
        $this->addSql('CREATE TABLE book_review (id INT AUTO_INCREMENT NOT NULL, author INT DEFAULT NULL, bookAuthor VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, timestamp DATETIME NOT NULL, title VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, summaryReviewer VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, review VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, INDEX IDX_50948A4BBDAFD8C8 (author), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci, username_canonical VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci, email VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci, email_canonical VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL COLLATE utf8_unicode_ci, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', first_name VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, UNIQUE INDEX UNIQ_8D93D649C05FB297 (confirmation_token), UNIQUE INDEX UNIQ_8D93D64992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_8D93D649A0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE users_workshop (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci, username_canonical VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci, email VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci, email_canonical VARCHAR(180) NOT NULL COLLATE utf8_unicode_ci, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL COLLATE utf8_unicode_ci, password VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL COLLATE utf8_unicode_ci, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', UNIQUE INDEX UNIQ_E02E291EC05FB297 (confirmation_token), UNIQUE INDEX UNIQ_E02E291E92FC23A8 (username_canonical), UNIQUE INDEX UNIQ_E02E291EA0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE workshop_entry (id INT AUTO_INCREMENT NOT NULL, author INT DEFAULT NULL, title VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, blog LONGTEXT NOT NULL COLLATE utf8_unicode_ci, timestamp DATETIME NOT NULL, INDEX IDX_C3A3CE6ABDAFD8C8 (author), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE book_review ADD CONSTRAINT FK_50948A4BBDAFD8C8 FOREIGN KEY (author) REFERENCES Registred_users (id)');
        $this->addSql('ALTER TABLE workshop_entry ADD CONSTRAINT FK_C3A3CE6ABDAFD8C8 FOREIGN KEY (author) REFERENCES users_workshop (id)');
        $this->addSql('DROP TABLE oauth2_access_tokens');
        $this->addSql('DROP TABLE oauth2_clients');
        $this->addSql('DROP TABLE oauth2_refresh_tokens');
        $this->addSql('DROP TABLE oauth2_auth_codes');
    }
}
