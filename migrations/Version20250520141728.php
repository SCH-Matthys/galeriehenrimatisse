<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520141728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE school_identificator (id INT AUTO_INCREMENT NOT NULL, school_name VARCHAR(255) NOT NULL, identificator VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, school_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, alias VARCHAR(255) DEFAULT NULL, INDEX IDX_8D93D649C32A47EE (school_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D649C32A47EE FOREIGN KEY (school_id) REFERENCES school_identificator (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D649C32A47EE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE school_identificator
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}
