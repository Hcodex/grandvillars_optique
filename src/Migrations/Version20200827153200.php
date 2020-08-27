<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200827153200 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media_media_category DROP FOREIGN KEY FK_A818C333E52EEF71');
        $this->addSql('ALTER TABLE media_media_category DROP FOREIGN KEY FK_A818C333EA9FDD75');
        $this->addSql('ALTER TABLE media_media_category ADD CONSTRAINT FK_A818C333E52EEF71 FOREIGN KEY (media_category_id) REFERENCES media_category (id)');
        $this->addSql('ALTER TABLE media_media_category ADD CONSTRAINT FK_A818C333EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media_media_category DROP FOREIGN KEY FK_A818C333EA9FDD75');
        $this->addSql('ALTER TABLE media_media_category DROP FOREIGN KEY FK_A818C333E52EEF71');
        $this->addSql('ALTER TABLE media_media_category ADD CONSTRAINT FK_A818C333EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_media_category ADD CONSTRAINT FK_A818C333E52EEF71 FOREIGN KEY (media_category_id) REFERENCES media_category (id) ON DELETE CASCADE');
    }
}
