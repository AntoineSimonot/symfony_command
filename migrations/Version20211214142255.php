<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211214142255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice ADD client_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE invoice ADD CONSTRAINT FK_90651744A3795DFD FOREIGN KEY (client_order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_90651744A3795DFD ON invoice (client_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE invoice DROP FOREIGN KEY FK_90651744A3795DFD');
        $this->addSql('DROP INDEX IDX_90651744A3795DFD ON invoice');
        $this->addSql('ALTER TABLE invoice DROP client_order_id');
    }
}
