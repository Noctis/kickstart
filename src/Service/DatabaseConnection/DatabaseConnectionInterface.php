<?php declare(strict_types=1);
namespace App\Service\DatabaseConnection;

interface DatabaseConnectionInterface
{
    /**
     * @param mixed  ...$params
     */
    public function run(string $statement, ...$params): array;

    /**
     * @param mixed  ...$params
     */
    public function row(string $statement, ...$params): ?array;

    public function column(string $statement, array $params = []): array;

    /**
     * @param mixed ...$params
     *
     * @return mixed
     */
    public function cell(string $statement, ...$params);

    public function insert(string $table, array $map): int;

    public function update(string $table, array $changes, array $conditions): int;

    public function escapeLikeValue(string $value): string;

    /**
     * @return mixed
     */
    public function tryFlatTransaction(callable $callback);
}