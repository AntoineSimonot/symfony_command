<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211122110355 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment ADD client_order_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payment ADD CONSTRAINT FK_6D28840DA3795DFD FOREIGN KEY (client_order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_6D28840DA3795DFD ON payment (client_order_id)');
        $this->addSql('ALTER TABLE product ADD client_order_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA3795DFD FOREIGN KEY (client_order_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADA3795DFD ON product (client_order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment DROP FOREIGN KEY FK_6D28840DA3795DFD');
        $this->addSql('DROP INDEX IDX_6D28840DA3795DFD ON payment');
        $this->addSql('ALTER TABLE payment DROP client_order_id');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA3795DFD');
        $this->addSql('DROP INDEX IDX_D34A04ADA3795DFD ON product');
        $this->addSql('ALTER TABLE product DROP client_order_id');
    }
}
