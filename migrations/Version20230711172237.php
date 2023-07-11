<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230711172237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_like_posts RENAME INDEX idx_afd956a667b3b43d TO IDX_E8523C8967B3B43D');
        $this->addSql('ALTER TABLE users_like_posts RENAME INDEX idx_afd956a6a1bf7ce2 TO IDX_E8523C89A1BF7CE2');
        $this->addSql('ALTER TABLE users_favorite_posts ADD CONSTRAINT FK_406D086B67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_favorite_posts ADD CONSTRAINT FK_406D086BA1BF7CE2 FOREIGN KEY (blog_posts_id) REFERENCES blog_posts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE users_favorite_posts RENAME INDEX idx_afd956a667b3b43d TO IDX_406D086B67B3B43D');
        $this->addSql('ALTER TABLE users_favorite_posts RENAME INDEX idx_afd956a6a1bf7ce2 TO IDX_406D086BA1BF7CE2');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users_favorite_posts DROP FOREIGN KEY FK_406D086B67B3B43D');
        $this->addSql('ALTER TABLE users_favorite_posts DROP FOREIGN KEY FK_406D086BA1BF7CE2');
        $this->addSql('ALTER TABLE users_favorite_posts RENAME INDEX idx_406d086b67b3b43d TO IDX_AFD956A667B3B43D');
        $this->addSql('ALTER TABLE users_favorite_posts RENAME INDEX idx_406d086ba1bf7ce2 TO IDX_AFD956A6A1BF7CE2');
        $this->addSql('ALTER TABLE users_like_posts RENAME INDEX idx_e8523c8967b3b43d TO IDX_AFD956A667B3B43D');
        $this->addSql('ALTER TABLE users_like_posts RENAME INDEX idx_e8523c89a1bf7ce2 TO IDX_AFD956A6A1BF7CE2');
    }
}
