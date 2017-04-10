<?php

namespace Leon\Database;

use Leon\Configuration\Database\Database as DatabaseConfiguration;

interface DatabaseInterface
{
    public function __construct(DatabaseConfiguration $databaseConfiguration);

    public function insertOne(string $tableName, array $entity);

    public function updateOne(string $tableName, array $entity, $primaryKey);

    public function getAll(string $tableName, string $orderBy = 'name ASC');

    public function getOneById(string $tableName, $primaryKey);

    public function deleteOneById(string $tableName, $primaryKey);

    public function existsById(string $tableName, $primaryKey);

    public function getNumQueries();

    public function select(string $query, array $params = []);

    public function selectOne(string $query, array $params = []);

    public function update(string $query, array $params = []);

    public function insert(string $query, array $params = []);

    public function command(string $query);

    public function delete(string $query, array $params = []);
}
