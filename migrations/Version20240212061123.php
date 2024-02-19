<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212061123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial setup with user roles';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        
        // Prompt for user roles
        $roles = $this->ask('Enter roles (comma-separated): ');

        // Insert initial user data
        $this->addSql('INSERT INTO "user" (email, roles, password) VALUES (:email, :roles, :password)', [
            'email' => $this->ask('Enter email: '),
            'roles' => [$role],
            'password' => password_hash($this->ask('Enter password: '), PASSWORD_DEFAULT),
        ]);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP TABLE "user"');
    }
}
