<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250325104735Filldefaultrole extends AbstractMigration
{
    private array $defaultRole = [
        'ROLE_ADMIN',
        'ROLE_CUSTOMER',
        ];
    public function up(Schema $schema): void
    {
        foreach ($this->defaultRole as $role) {
            $this->addSql("INSERT INTO role (name) VALUES ('$role')");
        }
    }

    public function down(Schema $schema): void
    {
        foreach ($this->defaultRole as $role) {
            $this->addSql("DELETE FROM role WHERE name = '$role'");
        }
        $this->addSql("ALTER TABLE role AUTO_INCREMENT = 1");
    }
}
