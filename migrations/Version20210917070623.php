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
        $this->addSql("INSERT INTO etat (id, libelle) VALUES ('1', 'Créer'), ('2', 'Ouverte'), ('3', 'Clôturée'), ('4', 'En cours'), ('5', 'Passée'), ('6', 'Annulée'), ('7', 'Supprimée '), ('8', 'Archivée')");
        $this->addSql("INSERT INTO site (id, nom) VALUES ('1', 'ENI-NANTES'), ('2', 'ENI-QUIMPER'), ('3', 'ENI-RENNES'), ('4', 'ENI-VANNES')");
        $this->addSql("INSERT INTO ville(id,nom,code_postal) VALUES ('1','QUIMPER','29000'),('2','NANTES','44000'),('3','RENNES','35000'),('4','VANNES','56000')");
        $this->addSql("INSERT INTO participants(pseudo, roles, password, nom, prenom, telephone, mail, administrateur, actif, site_id, ma_photo) 
                VALUES ('Nokes','[]','$2y$13\$mzExiq0BycvE6g7Fsg3gSuxFyzkYO8VYlDbLfprloDqx7gb5IDyla','Chirol','Lilian','0696123456','exemple1@outlook.fr',0,1,1,NULL),
                       ('LE GRAND REGIS','[]','$2y$13\$mzExiq0BycvE6g7Fsg3gSuxFyzkYO8VYlDbLfprloDqx7gb5IDyla','Fischer','Regis','0696123456','exemple1@outlook.fr',0,1,4,NULL),
                       ('Agadmator','[]','$2y$13\$mzExiq0BycvE6g7Fsg3gSuxFyzkYO8VYlDbLfprloDqx7gb5IDyla','Moundoyi','Harold','0696123456','exemple1@outlook.fr',0,1,1,NULL)");
        $this->addSql("INSERT INTO lieu(ville_id, nom, rue, latitude, longitude, url) 
                VALUES  ('1','Manoir du Kinkiz','75 Chem. du Quinquis','47.971493','-4.045075',NULL),
                       ('1','Jardin exotique de la Retraite','35 Rue Élie Freron','47.99748','-4.102516',NULL),
                       ('2','Ducs de Bretagne','4 Pl. Marc Elder','47.216097','-1.549996',NULL),
                       ('2','Le Parc de Porcé','44 rue des Dervallières','47.216097','-1.549996',NULL), 
                        ('3','L\'opéra de Rennes','4 Place de Mairie','48.111225','-1.678723',NULL),
                       ('3','Musée des Beaux-Arts','20 Quai Emile Zola','48.109623','-1.674941',NULL),
                       ('4','Les Remparts','Rue de l\'Arche','47.534864','-2.877519',NULL),
                       ('4','La Cohue','Place Saint-Pierre','47.657603','-2.757504',NULL)
        ");
        $this->addSql("INSERT INTO sortie(lieu_id, etat_id, site_id, organisateur_id, nom, date_heure_debut,duree, date_limite_inscription, nb_inscription_max, infos_sortie, motif)
                VALUES (1,5,1,1,'Aquabike','2021-08-23 11:14:00',90,'2021-08-22 11:14:00',8,'Premier cours d\'aquabike pour la promo',NULL),
                       (1,7,2,2,'Karaoke','2021-06-24 11:14:00',180,'2021-06-22 11:14:00',12,'soiree karaoke',NULL),
                       (1,8,1,1,'Aquabike','2021-05-24 11:14:00',90,'2021-04-22 11:14:00',8,'Premier cours d\'aquabike pour la promo',NULL)
                ");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
