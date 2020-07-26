<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200726151303 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE time_table (id INT AUTO_INCREMENT NOT NULL, mon_am VARCHAR(10) NOT NULL, mon_pm VARCHAR(10) NOT NULL, tue_am VARCHAR(10) NOT NULL, tue_pm VARCHAR(10) NOT NULL, wed_am VARCHAR(10) NOT NULL, wed_pm VARCHAR(10) NOT NULL, thu_am VARCHAR(10) NOT NULL, thu_pm VARCHAR(10) NOT NULL, fri_am VARCHAR(10) NOT NULL, fri_pm VARCHAR(10) NOT NULL, sat_am VARCHAR(10) NOT NULL, sat_pm VARCHAR(10) NOT NULL, sun_am VARCHAR(10) NOT NULL, sun_pm VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE time_table');
    }
}
