<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220228143423 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE credit (id INT AUTO_INCREMENT NOT NULL, num_compte_id INT DEFAULT NULL, operation_credit_id INT DEFAULT NULL, mont_credit INT NOT NULL, datepe DATE NOT NULL, datede DATE NOT NULL, duree_c INT NOT NULL, echeance DATE NOT NULL, taux_interet INT NOT NULL, decision TINYINT(1) NOT NULL, etat_credit VARCHAR(255) NOT NULL, type_credit VARCHAR(255) NOT NULL, INDEX IDX_1CC16EFE801B12FC (num_compte_id), INDEX IDX_1CC16EFE5DEAA61B (operation_credit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operation_credit (id INT AUTO_INCREMENT NOT NULL, date_op DATE NOT NULL, mont_payer INT NOT NULL, echeance DATE NOT NULL, taux_interet INT NOT NULL, solvabilite INT NOT NULL, type_operation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFE801B12FC FOREIGN KEY (num_compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE credit ADD CONSTRAINT FK_1CC16EFE5DEAA61B FOREIGN KEY (operation_credit_id) REFERENCES operation_credit (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE credit DROP FOREIGN KEY FK_1CC16EFE5DEAA61B');
        $this->addSql('DROP TABLE credit');
        $this->addSql('DROP TABLE operation_credit');
    }
}
