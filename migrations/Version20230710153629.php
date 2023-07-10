<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230710153629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE users_blog_posts (users_id INT NOT NULL, blog_posts_id INT NOT NULL, INDEX IDX_AFD956A667B3B43D (users_id), INDEX IDX_AFD956A6A1BF7CE2 (blog_posts_id), PRIMARY KEY(users_id, blog_posts_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users_blog_posts ADD CONSTRAINT FK_AFD956A667B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_blog_posts ADD CONSTRAINT FK_AFD956A6A1BF7CE2 FOREIGN KEY (blog_posts_id) REFERENCES blog_posts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_posts ADD num_of_likes INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_blog_posts DROP FOREIGN KEY FK_AFD956A667B3B43D');
        $this->addSql('ALTER TABLE users_blog_posts DROP FOREIGN KEY FK_AFD956A6A1BF7CE2');
        $this->addSql('DROP TABLE users_blog_posts');
        $this->addSql('ALTER TABLE blog_posts DROP num_of_likes');
    }
}
