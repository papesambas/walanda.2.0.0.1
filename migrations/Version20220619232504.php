<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220619232504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE publications (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, titre VARCHAR(255) NOT NULL, slug VARCHAR(128) NOT NULL, contenu LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, featured_image VARCHAR(255) DEFAULT NULL, is_verify TINYINT(1) NOT NULL, is_show TINYINT(1) NOT NULL, INDEX IDX_32783AF4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publications_users (publications_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_724D0BD0AFFB3979 (publications_id), INDEX IDX_724D0BD067B3B43D (users_id), PRIMARY KEY(publications_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE publications ADD CONSTRAINT FK_32783AF4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE publications_users ADD CONSTRAINT FK_724D0BD0AFFB3979 FOREIGN KEY (publications_id) REFERENCES publications (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publications_users ADD CONSTRAINT FK_724D0BD067B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publications_users DROP FOREIGN KEY FK_724D0BD0AFFB3979');
        $this->addSql('DROP TABLE publications');
        $this->addSql('DROP TABLE publications_users');
    }
}
