<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201128190007 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jugador (id INT AUTO_INCREMENT NOT NULL, posicio_id INT NOT NULL, nom VARCHAR(255) NOT NULL, sobrenom VARCHAR(255) NOT NULL, equip VARCHAR(255) NOT NULL, INDEX IDX_527D6F184AEFAF61 (posicio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posicio (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jugador ADD CONSTRAINT FK_527D6F184AEFAF61 FOREIGN KEY (posicio_id) REFERENCES posicio (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jugador DROP FOREIGN KEY FK_527D6F184AEFAF61');
        $this->addSql('DROP TABLE jugador');
        $this->addSql('DROP TABLE posicio');
    }
}
