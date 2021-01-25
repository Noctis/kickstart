<?php declare(strict_types=1);
namespace Noctis\KickStart\Repository;

use Noctis\Database\Connection\DatabaseConnectionInterface;

abstract class DatabaseRepository
{
    protected DatabaseConnectionInterface $db;

    public function __construct(DatabaseConnectionInterface $db)
    {
        $this->db = $db;
    }
}