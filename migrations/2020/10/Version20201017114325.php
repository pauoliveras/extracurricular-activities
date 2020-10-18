<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201017114325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE waiting_activity (id CHAR(36) NOT NULL COMMENT \'(DC2Type:identity)\', waiting_candidate_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:identity)\', activity_code VARCHAR(255) NOT NULL, requested_order INT NOT NULL, INDEX IDX_8DF85473F3BC1DCF (waiting_candidate_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE waiting_activity ADD CONSTRAINT FK_8DF85473F3BC1DCF FOREIGN KEY (waiting_candidate_id) REFERENCES waiting_candidate (id)');
        $this->addSql('ALTER TABLE waiting_candidate DROP waiting_activities');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE waiting_activity');
        $this->addSql('ALTER TABLE waiting_candidate ADD waiting_activities JSON DEFAULT NULL');
    }
}
