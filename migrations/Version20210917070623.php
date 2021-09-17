<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210917070623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("INSERT INTO etat (id, libelle) VALUES ('1', 'Créer'), ('2', 'Ouverte'), ('3', 'Clôturer'), ('4', 'Activité en cours'), ('5', 'Passer'), ('6', 'Annuler'), ('7', 'supprimer '), ('8', 'archivé')");
        $this->addSql("INSERT INTO site (id, nom) VALUES ('1', 'ENI-NANTES'), ('2', 'ENI-QUIMPER'), ('3', 'ENI-RENNES'), ('4', 'ENI-VANNES')");
        $this->addSql("INSERT INTO ville(id,nom,code_postal) VALUES ('1','QUIMPER','29000'),('2','NANTES','44000'),('3','RENNES','35000'),('4','VANNES','56000')");
        $this->addSql("INSERT INTO participants(pseudo, roles, password, nom, prenom, telephone, mail, administrateur, actif, site_id, ma_photo) 
                VALUES ('Nokes','[]','$2y$13\$mzExiq0BycvE6g7Fsg3gSuxFyzkYO8VYlDbLfprloDqx7gb5IDyla','Chirol','Lilian','0696123456','exemple1@outlook.fr',0,1,1,NULL),
                       ('LE GRAND REGIS','[]','$2y$13\$mzExiq0BycvE6g7Fsg3gSuxFyzkYO8VYlDbLfprloDqx7gb5IDyla','Fischer','Regis','0696123456','exemple1@outlook.fr',0,1,4,NULL),
                       ('Agadmator','[]','$2y$13\$mzExiq0BycvE6g7Fsg3gSuxFyzkYO8VYlDbLfprloDqx7gb5IDyla','Moundoyi','Harold','0696123456','exemple1@outlook.fr',0,1,1,NULL)");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
