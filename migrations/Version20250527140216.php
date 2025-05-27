<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250527140216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE artwork (id INT AUTO_INCREMENT NOT NULL, gallery_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_881FC5764E7AF8F (gallery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE artwork ADD CONSTRAINT FK_881FC5764E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE artwork DROP FOREIGN KEY FK_881FC5764E7AF8F
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE artwork
        SQL);
    }
}
