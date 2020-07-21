<?php declare(strict_types=1);
namespace App\Repository;

use App\Service\DatabaseConnection\DatabaseConnectionInterface;

abstract class DatabaseRepository
{
    /** @var DatabaseConnectionInterface */
    protected $db;

    public function __construct(DatabaseConnectionInterface $db)
    {
        $this->db = $db;
    }
}