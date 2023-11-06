<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231102183133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD year VARCHAR(5) DEFAULT NULL, ADD externallink VARCHAR(255) DEFAULT \'#\', ADD youtubeurl VARCHAR(255) DEFAULT \'#\', ADD twitterurl VARCHAR(255) DEFAULT \'#\', ADD instagramurl VARCHAR(255) DEFAULT \'#\', ADD facebookurl VARCHAR(255) DEFAULT \'#\', ADD googleplusurl VARCHAR(255) DEFAULT \'#\', ADD linkedinurl VARCHAR(255) DEFAULT \'#\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP year, DROP externallink, DROP youtubeurl, DROP twitterurl, DROP instagramurl, DROP facebookurl, DROP googleplusurl, DROP linkedinurl');
    }
}
