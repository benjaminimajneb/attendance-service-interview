<?php

declare(strict_types=1);

namespace App\Database;

interface DatabaseInterface
{
    /**
     * Insert a new record into the specified table.
     *
     * @param string $table Table name
     * @param array<string, mixed> $data Column => value pairs
     * @return void
     */
    public function insert(string $table, array $data): void;

    /**
     * Find a single record matching the criteria.
     *
     * @param string $table Table name
     * @param array<string, mixed> $criteria Column => value pairs for WHERE clause
     * @return array<string, mixed>|null Record data or null if not found
     */
    public function findOne(string $table, array $criteria): ?array;

    /**
     * Find all records matching the criteria.
     *
     * @param string $table Table name
     * @param array<string, mixed> $criteria Column => value pairs for WHERE clause
     * @return array<int, array<string, mixed>> Array of records
     */
    public function findAll(string $table, array $criteria = []): array;
}
