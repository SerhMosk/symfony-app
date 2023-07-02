<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\DBAL\Connection;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230702134813 extends AbstractMigration
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    private function createProduct(string $title, int $price): void
    {
        $this->connection->insert('product', [
            'title' => $title,
            'price' => $price,
        ]);
    }
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->createProduct('Sample Product', 10);
    }

    public function down(Schema $schema): void
    {
        return;
    }
}
