<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231029174249 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorites (recipe_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_E46960F559D8A214 (recipe_id), INDEX IDX_E46960F5A76ED395 (user_id), PRIMARY KEY(recipe_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, isuseronhomepageslider_id INT DEFAULT NULL, about LONGTEXT DEFAULT NULL, designation VARCHAR(255) DEFAULT NULL, team TINYINT(1) DEFAULT 0 NOT NULL, nickname VARCHAR(30) NOT NULL, slug VARCHAR(30) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, avatar_name VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649A188FE64 (nickname), UNIQUE INDEX UNIQ_8D93D649989D9B62 (slug), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649FF1B2680 (isuseronhomepageslider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F559D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE favorites ADD CONSTRAINT FK_E46960F5A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649FF1B2680 FOREIGN KEY (isuseronhomepageslider_id) REFERENCES homepage_hero_setting (id)');
        $this->addSql('ALTER TABLE recipe ADD user_id INT DEFAULT NULL, ADD views INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_DA88B137A76ED395 ON recipe (user_id)');
        $this->addSql('ALTER TABLE review ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
        $this->addSql('ALTER TABLE testimonial ADD user_id INT DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE testimonial ADD CONSTRAINT FK_E6BDCDF7A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_E6BDCDF7A76ED395 ON testimonial (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE testimonial DROP FOREIGN KEY FK_E6BDCDF7A76ED395');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F559D8A214');
        $this->addSql('ALTER TABLE favorites DROP FOREIGN KEY FK_E46960F5A76ED395');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649FF1B2680');
        $this->addSql('DROP TABLE favorites');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP INDEX IDX_DA88B137A76ED395 ON recipe');
        $this->addSql('ALTER TABLE recipe DROP user_id, DROP views');
        $this->addSql('DROP INDEX IDX_794381C6A76ED395 ON review');
        $this->addSql('ALTER TABLE review DROP user_id');
        $this->addSql('DROP INDEX IDX_E6BDCDF7A76ED395 ON testimonial');
        $this->addSql('ALTER TABLE testimonial DROP user_id, DROP deleted_at');
    }
}
