<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230410043102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favourite_fruit (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, fruit_id INT NOT NULL, INDEX IDX_17E53E2A76ED395 (user_id), INDEX IDX_17E53E2BAC115F0 (fruit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favourite_fruit ADD CONSTRAINT FK_17E53E2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favourite_fruit ADD CONSTRAINT FK_17E53E2BAC115F0 FOREIGN KEY (fruit_id) REFERENCES fruit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favourite_fruit DROP FOREIGN KEY FK_17E53E2A76ED395');
        $this->addSql('ALTER TABLE favourite_fruit DROP FOREIGN KEY FK_17E53E2BAC115F0');
        $this->addSql('DROP TABLE favourite_fruit');
    }
}
