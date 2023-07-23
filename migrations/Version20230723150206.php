<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230723150206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE post_translations (id INT AUTO_INCREMENT NOT NULL, post_id_id INT NOT NULL, locale VARCHAR(2) NOT NULL, body LONGTEXT NOT NULL, INDEX IDX_6D8AA754E85F12B8 (post_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_translations ADD CONSTRAINT FK_6D8AA754E85F12B8 FOREIGN KEY (post_id_id) REFERENCES blog_posts (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_translations DROP FOREIGN KEY FK_6D8AA754E85F12B8');
        $this->addSql('DROP TABLE post_translations');
    }
}
