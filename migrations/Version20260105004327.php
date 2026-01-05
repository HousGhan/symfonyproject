<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260105004327 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointement ADD status VARCHAR(255) DEFAULT NULL, ADD price NUMERIC(10, 2) DEFAULT NULL, ADD payed TINYINT DEFAULT NULL');
        $this->addSql('ALTER TABLE prescription CHANGE medicaments medicaments VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE appointement DROP status, DROP price, DROP payed');
        $this->addSql('ALTER TABLE prescription CHANGE medicaments medicaments TEXT DEFAULT NULL COLLATE `utf8mb4_bin`');
    }
}
