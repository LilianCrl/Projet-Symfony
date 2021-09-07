<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210907142602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participants_sortie (participants_id INT NOT NULL, sortie_id INT NOT NULL, INDEX IDX_F1D88C60838709D5 (participants_id), INDEX IDX_F1D88C60CC72D953 (sortie_id), PRIMARY KEY(participants_id, sortie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE participants_sortie ADD CONSTRAINT FK_F1D88C60838709D5 FOREIGN KEY (participants_id) REFERENCES participants (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participants_sortie ADD CONSTRAINT FK_F1D88C60CC72D953 FOREIGN KEY (sortie_id) REFERENCES sortie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participants ADD site_id INT NOT NULL');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_71697092F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('CREATE INDEX IDX_71697092F6BD1646 ON participants (site_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE participants_sortie');
        $this->addSql('ALTER TABLE participants DROP FOREIGN KEY FK_71697092F6BD1646');
        $this->addSql('DROP INDEX IDX_71697092F6BD1646 ON participants');
        $this->addSql('ALTER TABLE participants DROP site_id');
    }
}
