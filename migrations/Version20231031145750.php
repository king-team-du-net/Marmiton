<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031145750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, image_name VARCHAR(50) DEFAULT NULL, content LONGTEXT NOT NULL, readtime INT DEFAULT NULL, title VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, views INT DEFAULT NULL, published_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_23A0E66989D9B62 (slug), INDEX IDX_23A0E66F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_article_like (article_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_9CEDBBB07294869C (article_id), INDEX IDX_9CEDBBB0A76ED395 (user_id), PRIMARY KEY(article_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE badge (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, name VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_FEF0481D989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_article (badge_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_300B23CCF7A2C2FC (badge_id), INDEX IDX_300B23CC7294869C (article_id), PRIMARY KEY(badge_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, name VARCHAR(128) NOT NULL, slug VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_64C19C1989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_article (category_id INT NOT NULL, article_id INT NOT NULL, INDEX IDX_C5E24E1812469DE2 (category_id), INDEX IDX_C5E24E187294869C (article_id), PRIMARY KEY(category_id, article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, article_id INT NOT NULL, content LONGTEXT NOT NULL, published_at DATETIME NOT NULL, ip VARCHAR(46) DEFAULT NULL, is_approved TINYINT(1) DEFAULT 0 NOT NULL, is_rgpd TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_article_like ADD CONSTRAINT FK_9CEDBBB07294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_article_like ADD CONSTRAINT FK_9CEDBBB0A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_article ADD CONSTRAINT FK_300B23CCF7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_article ADD CONSTRAINT FK_300B23CC7294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_article ADD CONSTRAINT FK_C5E24E1812469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_article ADD CONSTRAINT FK_C5E24E187294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE recipe ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_DA88B137A76ED395 ON recipe (user_id)');
        $this->addSql('ALTER TABLE user ADD suspended TINYINT(1) DEFAULT 0 NOT NULL, ADD firstname VARCHAR(20) NOT NULL, ADD lastname VARCHAR(20) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66F675F31B');
        $this->addSql('ALTER TABLE user_article_like DROP FOREIGN KEY FK_9CEDBBB07294869C');
        $this->addSql('ALTER TABLE user_article_like DROP FOREIGN KEY FK_9CEDBBB0A76ED395');
        $this->addSql('ALTER TABLE tag_article DROP FOREIGN KEY FK_300B23CCF7A2C2FC');
        $this->addSql('ALTER TABLE tag_article DROP FOREIGN KEY FK_300B23CC7294869C');
        $this->addSql('ALTER TABLE category_article DROP FOREIGN KEY FK_C5E24E1812469DE2');
        $this->addSql('ALTER TABLE category_article DROP FOREIGN KEY FK_C5E24E187294869C');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C7294869C');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE user_article_like');
        $this->addSql('DROP TABLE badge');
        $this->addSql('DROP TABLE tag_article');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_article');
        $this->addSql('DROP TABLE comment');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137A76ED395');
        $this->addSql('DROP INDEX IDX_DA88B137A76ED395 ON recipe');
        $this->addSql('ALTER TABLE recipe DROP user_id');
        $this->addSql('ALTER TABLE `user` DROP suspended, DROP firstname, DROP lastname');
    }
}
