<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200529231231 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, parent_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, introduction LONGTEXT NOT NULL, content LONGTEXT DEFAULT NULL, cover_image VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, statut TINYINT(1) NOT NULL, INDEX IDX_AF3C677912469DE2 (category_id), INDEX IDX_AF3C6779727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actualite (id INT AUTO_INCREMENT NOT NULL, publication_id INT NOT NULL, debut_publication DATETIME NOT NULL, fin_publication DATETIME DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_5492819738B217A7 (publication_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE galerie (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, cover_image VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, order_by TINYINT(1) NOT NULL, statut TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, publication_id INT DEFAULT NULL, galerie_id INT DEFAULT NULL, caption VARCHAR(255) DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_C53D045F38B217A7 (publication_id), INDEX IDX_C53D045F825396CB (galerie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lien_utile (id INT AUTO_INCREMENT NOT NULL, publication_id INT NOT NULL, url VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_32819538B217A7 (publication_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login_failed_attempt (id INT AUTO_INCREMENT NOT NULL, ip_address VARCHAR(50) DEFAULT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', username VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, rubrique1_id INT DEFAULT NULL, rubrique2_id INT DEFAULT NULL, rubrique3_id INT DEFAULT NULL, rubrique4_id INT DEFAULT NULL, rubrique5_id INT DEFAULT NULL, rubrique6_id INT DEFAULT NULL, rubrique7_id INT DEFAULT NULL, rubrique8_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_7D053A9369879F0D (rubrique1_id), UNIQUE INDEX UNIQ_7D053A937B3230E3 (rubrique2_id), UNIQUE INDEX UNIQ_7D053A93C38E5786 (rubrique3_id), UNIQUE INDEX UNIQ_7D053A935E596F3F (rubrique4_id), UNIQUE INDEX UNIQ_7D053A93E6E5085A (rubrique5_id), UNIQUE INDEX UNIQ_7D053A93F450A7B4 (rubrique6_id), UNIQUE INDEX UNIQ_7D053A934CECC0D1 (rubrique7_id), UNIQUE INDEX UNIQ_7D053A93148FD087 (rubrique8_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, publication_id INT NOT NULL, url VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, title VARCHAR(255) NOT NULL, INDEX IDX_939F454438B217A7 (publication_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, role_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, informations VARCHAR(255) DEFAULT NULL, hash VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, date_token DATETIME DEFAULT NULL, INDEX IDX_8D93D649D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C677912469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779727ACA70 FOREIGN KEY (parent_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE actualite ADD CONSTRAINT FK_5492819738B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F825396CB FOREIGN KEY (galerie_id) REFERENCES galerie (id)');
        $this->addSql('ALTER TABLE lien_utile ADD CONSTRAINT FK_32819538B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A9369879F0D FOREIGN KEY (rubrique1_id) REFERENCES publication (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A937B3230E3 FOREIGN KEY (rubrique2_id) REFERENCES publication (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93C38E5786 FOREIGN KEY (rubrique3_id) REFERENCES publication (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A935E596F3F FOREIGN KEY (rubrique4_id) REFERENCES publication (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93E6E5085A FOREIGN KEY (rubrique5_id) REFERENCES publication (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93F450A7B4 FOREIGN KEY (rubrique6_id) REFERENCES publication (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A934CECC0D1 FOREIGN KEY (rubrique7_id) REFERENCES publication (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93148FD087 FOREIGN KEY (rubrique8_id) REFERENCES publication (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F454438B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779727ACA70');
        $this->addSql('ALTER TABLE actualite DROP FOREIGN KEY FK_5492819738B217A7');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F38B217A7');
        $this->addSql('ALTER TABLE lien_utile DROP FOREIGN KEY FK_32819538B217A7');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A9369879F0D');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A937B3230E3');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93C38E5786');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A935E596F3F');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93E6E5085A');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93F450A7B4');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A934CECC0D1');
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93148FD087');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F454438B217A7');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C677912469DE2');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F825396CB');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE actualite');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE galerie');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE lien_utile');
        $this->addSql('DROP TABLE login_failed_attempt');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE user');
    }
}
