<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230806152248 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql(
            "INSERT INTO gender VALUES (1, 'M'), (2, 'K')"
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql(
            "TRUNCATE TABLE gender"
        );
    }
}
