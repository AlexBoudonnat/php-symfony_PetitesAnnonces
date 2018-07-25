<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724113337 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_D34A04AD9D86650F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, user_id_id, name, release_on, description, picture_name, localisation, category, other_details FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER NOT NULL, name CLOB NOT NULL COLLATE BINARY, release_on DATETIME NOT NULL, description CLOB DEFAULT NULL COLLATE BINARY, picture_name VARCHAR(255) DEFAULT NULL COLLATE BINARY, localisation VARCHAR(255) DEFAULT NULL COLLATE BINARY, category VARCHAR(255) DEFAULT NULL COLLATE BINARY, other_details CLOB DEFAULT NULL COLLATE BINARY, allowed BOOLEAN DEFAULT NULL, CONSTRAINT FK_D34A04AD9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product (id, user_id_id, name, release_on, description, picture_name, localisation, category, other_details) SELECT id, user_id_id, name, release_on, description, picture_name, localisation, category, other_details FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE INDEX IDX_D34A04AD9D86650F ON product (user_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_D34A04AD9D86650F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, user_id_id, name, release_on, description, picture_name, localisation, category, other_details FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER NOT NULL, name CLOB NOT NULL, release_on DATETIME NOT NULL, description CLOB DEFAULT NULL, picture_name VARCHAR(255) DEFAULT NULL, localisation VARCHAR(255) DEFAULT NULL, category VARCHAR(255) DEFAULT NULL, other_details CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO product (id, user_id_id, name, release_on, description, picture_name, localisation, category, other_details) SELECT id, user_id_id, name, release_on, description, picture_name, localisation, category, other_details FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE INDEX IDX_D34A04AD9D86650F ON product (user_id_id)');
    }
}
