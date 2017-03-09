<?php

namespace Leon\Database;

interface DatabaseInterface
{
    public function __construct();

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
