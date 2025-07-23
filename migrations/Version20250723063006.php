<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250723063006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe (id_equipe INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, pays VARCHAR(100) NOT NULL, nom_capitaine VARCHAR(100) NOT NULL, email VARCHAR(100) NOT NULL, telephone VARCHAR(20) NOT NULL, date_creation DATETIME NOT NULL, UNIQUE INDEX UNIQ_2449BA15E7927C74 (email), UNIQUE INDEX UNIQ_2449BA15450FF010 (telephone), PRIMARY KEY(id_equipe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe_tournoi (equipe_id INT NOT NULL, tournoi_id INT NOT NULL, INDEX IDX_C0AFC5636D861B89 (equipe_id), INDEX IDX_C0AFC563F607770A (tournoi_id), PRIMARY KEY(equipe_id, tournoi_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joueur (id_joueur INT AUTO_INCREMENT NOT NULL, id_equipe_id INT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, poste VARCHAR(50) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_FD71A9C5EDB3A7AE (id_equipe_id), PRIMARY KEY(id_joueur)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE match_equipe (id_match_equipe INT AUTO_INCREMENT NOT NULL, id_match_id INT NOT NULL, id_equipe_id INT NOT NULL, role VARCHAR(10) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_852643147A654043 (id_match_id), INDEX IDX_85264314EDB3A7AE (id_equipe_id), PRIMARY KEY(id_match_equipe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matchs (id_match INT AUTO_INCREMENT NOT NULL, id_poule_id INT NOT NULL, id_tableau_id INT NOT NULL, id_tournoi_id INT NOT NULL, date_heure DATETIME NOT NULL, lieu VARCHAR(100) NOT NULL, phase VARCHAR(50) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_6B1E60417682834D (id_poule_id), INDEX IDX_6B1E604115E8556B (id_tableau_id), INDEX IDX_6B1E6041538DF7DD (id_tournoi_id), PRIMARY KEY(id_match)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poule (id_poule INT AUTO_INCREMENT NOT NULL, id_tournoi_id INT NOT NULL, nom VARCHAR(100) NOT NULL, categorie VARCHAR(20) DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_FA1FEB40538DF7DD (id_tournoi_id), PRIMARY KEY(id_poule)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poule_equipe (id_poule_equipe INT AUTO_INCREMENT NOT NULL, id_equipe_id INT NOT NULL, id_poule_id INT NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_EE99353EEDB3A7AE (id_equipe_id), INDEX IDX_EE99353E7682834D (id_poule_id), PRIMARY KEY(id_poule_equipe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prevision (id_prevision INT AUTO_INCREMENT NOT NULL, match_id INT NOT NULL, probabilite_victoire_equipe1 DOUBLE PRECISION NOT NULL, probabilite_victoire_equipe2 DOUBLE PRECISION NOT NULL, date_creation DATETIME NOT NULL, UNIQUE INDEX UNIQ_1EEB1DDE2ABEACD6 (match_id), PRIMARY KEY(id_prevision)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resultat (id_resultat INT AUTO_INCREMENT NOT NULL, match_id INT NOT NULL, score_equipe1 INT NOT NULL, score_equipe2 INT NOT NULL, fautes_equipe1 INT NOT NULL, fautes_equipe2 INT NOT NULL, cartons_jaunes_equipe1 INT NOT NULL, cartons_jaunes_equipe2 INT NOT NULL, cartons_rouges_equipe1 INT NOT NULL, cartons_rouges_equipe2 INT NOT NULL, date_creation DATETIME NOT NULL, UNIQUE INDEX UNIQ_E7DB5DE22ABEACD6 (match_id), PRIMARY KEY(id_resultat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE statistique_equipe_tournoi (id_stats INT AUTO_INCREMENT NOT NULL, id_equipe_id INT NOT NULL, id_tournoi_id INT NOT NULL, nb_matchs_joués INT NOT NULL, nb_victoires INT NOT NULL, nb_buts_marques INT NOT NULL, nb_buts_encaisses INT NOT NULL, nb_cartons INT NOT NULL, nb_fautes INT NOT NULL, INDEX IDX_FED543A1EDB3A7AE (id_equipe_id), INDEX IDX_FED543A1538DF7DD (id_tournoi_id), PRIMARY KEY(id_stats)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tableau (id_tableau INT AUTO_INCREMENT NOT NULL, id_tournoi_id INT NOT NULL, nom_phase VARCHAR(50) DEFAULT NULL, ordre VARCHAR(50) DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_C6744DB1538DF7DD (id_tournoi_id), PRIMARY KEY(id_tableau)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournoi (id_tournoi INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, lieu VARCHAR(100) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, date_creation DATETIME NOT NULL, PRIMARY KEY(id_tournoi)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe_tournoi ADD CONSTRAINT FK_C0AFC5636D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id_equipe)');
        $this->addSql('ALTER TABLE equipe_tournoi ADD CONSTRAINT FK_C0AFC563F607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id_tournoi)');
        $this->addSql('ALTER TABLE joueur ADD CONSTRAINT FK_FD71A9C5EDB3A7AE FOREIGN KEY (id_equipe_id) REFERENCES equipe (id_equipe)');
        $this->addSql('ALTER TABLE match_equipe ADD CONSTRAINT FK_852643147A654043 FOREIGN KEY (id_match_id) REFERENCES matchs (id_match)');
        $this->addSql('ALTER TABLE match_equipe ADD CONSTRAINT FK_85264314EDB3A7AE FOREIGN KEY (id_equipe_id) REFERENCES equipe (id_equipe)');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT FK_6B1E60417682834D FOREIGN KEY (id_poule_id) REFERENCES poule (id_poule)');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT FK_6B1E604115E8556B FOREIGN KEY (id_tableau_id) REFERENCES tableau (id_tableau)');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT FK_6B1E6041538DF7DD FOREIGN KEY (id_tournoi_id) REFERENCES tournoi (id_tournoi)');
        $this->addSql('ALTER TABLE poule ADD CONSTRAINT FK_FA1FEB40538DF7DD FOREIGN KEY (id_tournoi_id) REFERENCES tournoi (id_tournoi)');
        $this->addSql('ALTER TABLE poule_equipe ADD CONSTRAINT FK_EE99353EEDB3A7AE FOREIGN KEY (id_equipe_id) REFERENCES equipe (id_equipe)');
        $this->addSql('ALTER TABLE poule_equipe ADD CONSTRAINT FK_EE99353E7682834D FOREIGN KEY (id_poule_id) REFERENCES poule (id_poule)');
        $this->addSql('ALTER TABLE prevision ADD CONSTRAINT FK_1EEB1DDE2ABEACD6 FOREIGN KEY (match_id) REFERENCES matchs (id_match)');
        $this->addSql('ALTER TABLE resultat ADD CONSTRAINT FK_E7DB5DE22ABEACD6 FOREIGN KEY (match_id) REFERENCES matchs (id_match)');
        $this->addSql('ALTER TABLE statistique_equipe_tournoi ADD CONSTRAINT FK_FED543A1EDB3A7AE FOREIGN KEY (id_equipe_id) REFERENCES equipe (id_equipe)');
        $this->addSql('ALTER TABLE statistique_equipe_tournoi ADD CONSTRAINT FK_FED543A1538DF7DD FOREIGN KEY (id_tournoi_id) REFERENCES tournoi (id_tournoi)');
        $this->addSql('ALTER TABLE tableau ADD CONSTRAINT FK_C6744DB1538DF7DD FOREIGN KEY (id_tournoi_id) REFERENCES tournoi (id_tournoi)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe_tournoi DROP FOREIGN KEY FK_C0AFC5636D861B89');
        $this->addSql('ALTER TABLE equipe_tournoi DROP FOREIGN KEY FK_C0AFC563F607770A');
        $this->addSql('ALTER TABLE joueur DROP FOREIGN KEY FK_FD71A9C5EDB3A7AE');
        $this->addSql('ALTER TABLE match_equipe DROP FOREIGN KEY FK_852643147A654043');
        $this->addSql('ALTER TABLE match_equipe DROP FOREIGN KEY FK_85264314EDB3A7AE');
        $this->addSql('ALTER TABLE matchs DROP FOREIGN KEY FK_6B1E60417682834D');
        $this->addSql('ALTER TABLE matchs DROP FOREIGN KEY FK_6B1E604115E8556B');
        $this->addSql('ALTER TABLE matchs DROP FOREIGN KEY FK_6B1E6041538DF7DD');
        $this->addSql('ALTER TABLE poule DROP FOREIGN KEY FK_FA1FEB40538DF7DD');
        $this->addSql('ALTER TABLE poule_equipe DROP FOREIGN KEY FK_EE99353EEDB3A7AE');
        $this->addSql('ALTER TABLE poule_equipe DROP FOREIGN KEY FK_EE99353E7682834D');
        $this->addSql('ALTER TABLE prevision DROP FOREIGN KEY FK_1EEB1DDE2ABEACD6');
        $this->addSql('ALTER TABLE resultat DROP FOREIGN KEY FK_E7DB5DE22ABEACD6');
        $this->addSql('ALTER TABLE statistique_equipe_tournoi DROP FOREIGN KEY FK_FED543A1EDB3A7AE');
        $this->addSql('ALTER TABLE statistique_equipe_tournoi DROP FOREIGN KEY FK_FED543A1538DF7DD');
        $this->addSql('ALTER TABLE tableau DROP FOREIGN KEY FK_C6744DB1538DF7DD');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE equipe_tournoi');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('DROP TABLE match_equipe');
        $this->addSql('DROP TABLE matchs');
        $this->addSql('DROP TABLE poule');
        $this->addSql('DROP TABLE poule_equipe');
        $this->addSql('DROP TABLE prevision');
        $this->addSql('DROP TABLE resultat');
        $this->addSql('DROP TABLE statistique_equipe_tournoi');
        $this->addSql('DROP TABLE tableau');
        $this->addSql('DROP TABLE tournoi');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
