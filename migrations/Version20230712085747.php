<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230712085747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_categories DROP FOREIGN KEY FK_198B4FA9A1BF7CE2');
        $this->addSql('DROP INDEX IDX_198B4FA9A1BF7CE2 ON post_categories');
        $this->addSql('ALTER TABLE post_categories DROP blog_posts_id');
        $this->addSql('ALTER TABLE users ADD avatar VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP avatar');
        $this->addSql('ALTER TABLE post_categories ADD blog_posts_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post_categories ADD CONSTRAINT FK_198B4FA9A1BF7CE2 FOREIGN KEY (blog_posts_id) REFERENCES blog_posts (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_198B4FA9A1BF7CE2 ON post_categories (blog_posts_id)');
    }
}
