<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260513212531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE team_invitation DROP FOREIGN KEY `fk_team_inv_equipe`');
        $this->addSql('ALTER TABLE team_invitation DROP FOREIGN KEY `fk_team_inv_user`');
        $this->addSql('DROP TABLE stream_comment');
        $this->addSql('DROP TABLE team_invitation');
        $this->addSql('DROP TABLE video_comment');
        $this->addSql('DROP TABLE video_reaction');
        $this->addSql('ALTER TABLE join_request DROP motif, CHANGE statut status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE user DROP profile_image_url');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stream_comment (id INT AUTO_INCREMENT NOT NULL, stream_id INT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, body TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX idx_stream_comment_stream (stream_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE team_invitation (id INT AUTO_INCREMENT NOT NULL, equipe_id INT NOT NULL, user_id INT NOT NULL, status VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT \'invited\' NOT NULL COLLATE `utf8mb4_general_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX fk_team_inv_user (user_id), INDEX fk_team_inv_equipe (equipe_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE video_comment (id INT AUTO_INCREMENT NOT NULL, video_id INT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, body TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX idx_video_comment_video (video_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE video_reaction (id INT AUTO_INCREMENT NOT NULL, video_id INT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, type VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX idx_video_reaction_video (video_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE team_invitation ADD CONSTRAINT `fk_team_inv_equipe` FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team_invitation ADD CONSTRAINT `fk_team_inv_user` FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE join_request ADD motif LONGTEXT DEFAULT NULL, CHANGE status statut VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE `user` ADD profile_image_url TEXT DEFAULT NULL');
    }
}
