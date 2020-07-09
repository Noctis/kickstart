<?php declare(strict_types=1);
namespace App\Service\DatabaseConnection;

use ParagonIE\EasyDB\EasyDB as ActuallyEasyDB;
use ParagonIE\EasyDB\Factory;

final class EasyDB implements DatabaseConnectionInterface
{
    /** @var ActuallyEasyDB */
    private $db;

    public function __construct(string $dsn, string $user = null, string $password = null)
    {
        $this->db = Factory::fromArray([
            $dsn,
            $user,
            $password
        ]);
    }

    /**
     * @inheritDoc
     */
    public function run(string $statement, ...$params): array
    {
        return $this->db
            ->run($statement, ...$params);
    }

    public function row(string $statement, ...$params): ?array
    {
        return $this->db
            ->row($statement, ...$params);
    }

    /**
     * @inheritDoc
     */
    public function column(string $statement, array $params = []): array
    {
        return $this->db
            ->column($statement, $params);
    }

    /**
     * @inheritDoc
     */
    public function cell(string $statement, ...$params)
    {
        return $this->db
            ->cell($statement, ...$params);
    }

    /**
     * @inheritDoc
     */
    public function insert(string $table, array $map): int
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return (int)$this->db
            ->insertReturnId($table, $map);
    }

    /**
     * @inheritDoc
     */
    public function update(string $table, array $changes, array $conditions): int
    {
        return $this->db
            ->update($table, $changes, $conditions);
    }

    /**
     * @inheritDoc
     */
    public function escapeLikeValue(string $value): string
    {
        return $this->db
            ->escapeLikeValue($value);
    }

    /**
     * @inheritDoc
     */
    public function tryFlatTransaction(callable $callback)
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->db
            ->tryFlatTransaction($callback);
    }
}