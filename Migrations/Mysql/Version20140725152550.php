<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "In2code.SurfGui".       *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        */

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20140725152550 extends AbstractMigration
{
    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql');

        $this->addSql('CREATE TABLE in2code_surfgui_domain_model_git_branch (persistence_object_identifier VARCHAR(40) NOT NULL, name VARCHAR(255) NOT NULL, currentcommithash VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE in2code_surfgui_domain_model_git_repository (persistence_object_identifier VARCHAR(40) NOT NULL, url VARCHAR(255) NOT NULL, UNIQUE INDEX flow_identity_in2code_surfgui_domain_model_git_repository (url), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE in2code_surfgui_domain_model_git_repository_branches_join (surfgui_git_repository VARCHAR(40) NOT NULL, surfgui_git_branch VARCHAR(40) NOT NULL, INDEX IDX_5222F5DD9719BB2E (surfgui_git_repository), INDEX IDX_5222F5DDC8A3A288 (surfgui_git_branch), PRIMARY KEY(surfgui_git_repository, surfgui_git_branch)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE in2code_surfgui_domain_model_git_repository_tags_join (surfgui_git_repository VARCHAR(40) NOT NULL, surfgui_git_tag VARCHAR(40) NOT NULL, INDEX IDX_7ACF26839719BB2E (surfgui_git_repository), INDEX IDX_7ACF2683A4AE6E5C (surfgui_git_tag), PRIMARY KEY(surfgui_git_repository, surfgui_git_tag)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE in2code_surfgui_domain_model_git_tag (persistence_object_identifier VARCHAR(40) NOT NULL, version VARCHAR(255) NOT NULL, taggedcommithash VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE in2code_surfgui_domain_model_git_repository_branches_join ADD CONSTRAINT FK_5222F5DD9719BB2E FOREIGN KEY (surfgui_git_repository) REFERENCES in2code_surfgui_domain_model_git_repository (persistence_object_identifier)');
        $this->addSql('ALTER TABLE in2code_surfgui_domain_model_git_repository_branches_join ADD CONSTRAINT FK_5222F5DDC8A3A288 FOREIGN KEY (surfgui_git_branch) REFERENCES in2code_surfgui_domain_model_git_branch (persistence_object_identifier)');
        $this->addSql('ALTER TABLE in2code_surfgui_domain_model_git_repository_tags_join ADD CONSTRAINT FK_7ACF26839719BB2E FOREIGN KEY (surfgui_git_repository) REFERENCES in2code_surfgui_domain_model_git_repository (persistence_object_identifier)');
        $this->addSql('ALTER TABLE in2code_surfgui_domain_model_git_repository_tags_join ADD CONSTRAINT FK_7ACF2683A4AE6E5C FOREIGN KEY (surfgui_git_tag) REFERENCES in2code_surfgui_domain_model_git_tag (persistence_object_identifier)');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql');

        $this->addSql('ALTER TABLE in2code_surfgui_domain_model_git_repository_branches_join DROP FOREIGN KEY FK_5222F5DDC8A3A288');
        $this->addSql('ALTER TABLE in2code_surfgui_domain_model_git_repository_branches_join DROP FOREIGN KEY FK_5222F5DD9719BB2E');
        $this->addSql('ALTER TABLE in2code_surfgui_domain_model_git_repository_tags_join DROP FOREIGN KEY FK_7ACF26839719BB2E');
        $this->addSql('ALTER TABLE in2code_surfgui_domain_model_git_repository_tags_join DROP FOREIGN KEY FK_7ACF2683A4AE6E5C');
        $this->addSql('DROP TABLE in2code_surfgui_domain_model_git_branch');
        $this->addSql('DROP TABLE in2code_surfgui_domain_model_git_repository');
        $this->addSql('DROP TABLE in2code_surfgui_domain_model_git_repository_branches_join');
        $this->addSql('DROP TABLE in2code_surfgui_domain_model_git_repository_tags_join');
        $this->addSql('DROP TABLE in2code_surfgui_domain_model_git_tag');
    }
}
