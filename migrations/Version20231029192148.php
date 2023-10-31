<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231029192148 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_recipe (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, recipe_id INT DEFAULT NULL, status_id INT DEFAULT NULL, comment LONGTEXT DEFAULT NULL, rating INT DEFAULT 5, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_BFDAAA0AA76ED395 (user_id), INDEX IDX_BFDAAA0A59D8A214 (recipe_id), INDEX IDX_BFDAAA0A6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_recipe ADD CONSTRAINT FK_BFDAAA0AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user_recipe ADD CONSTRAINT FK_BFDAAA0A59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE user_recipe ADD CONSTRAINT FK_BFDAAA0A6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137A76ED395');
        $this->addSql('DROP INDEX IDX_DA88B137A76ED395 ON recipe');
        $this->addSql('ALTER TABLE recipe DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_recipe DROP FOREIGN KEY FK_BFDAAA0AA76ED395');
        $this->addSql('ALTER TABLE user_recipe DROP FOREIGN KEY FK_BFDAAA0A59D8A214');
        $this->addSql('ALTER TABLE user_recipe DROP FOREIGN KEY FK_BFDAAA0A6BF700BD');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE user_recipe');
        $this->addSql('ALTER TABLE recipe ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_DA88B137A76ED395 ON recipe (user_id)');
    }
}
