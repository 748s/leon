<?php

namespace Leon\Database;

use InvalidArgumentException;
use Leon\Database\DatabaseInterface;
use PDO;
use PDOException;

/**
 * Arm: ArrayRelationalMapping
 * A database abstraction class with behavior similar to an ORM
 *
 * @author Nick Wakeman <nick.wakeman@gmail.com>
 * @since  2016-10-07
 *
 * @todo add param matching and adding of ':' for all query types (just like put())
 */
class MySQL implements DatabaseInterface
{
    protected $db;
    protected $numQueries = 0;
    protected $primaryKeys = [];
    protected $tables = [];
    protected $type;

    public function __construct()
    {
        global $config;
        $databaseConfig = $config->getDatabase();
        $pdoOptions  = array(
            PDO::MYSQL_ATTR_FOUND_ROWS   => true,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        );
        $this->db = new PDO(
            "mysql:host={$databaseConfig->getHost()};dbname={$databaseConfig->getDatabase()}",
            $databaseConfig->getUser(),
            $databaseConfig->getPassword(),
            $pdoOptions
        );
        $query = 'SELECT TABLE_NAME, COLUMN_NAME, COLUMN_KEY FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = :tableSchema';
        $result = $this->select($query, [':tableSchema' => $databaseConfig->getDatabase()]);
        foreach ($result as $tableColumn) {
            if ($tableColumn['COLUMN_KEY'] === 'PRI') {
              $this->primaryKeys[$tableColumn['TABLE_NAME']] = $tableColumn['COLUMN_NAME'];
            } else {
              $this->tables[$tableColumn['TABLE_NAME']][] = $tableColumn['COLUMN_NAME'];
            }
        }
    }

    public function insertOne(string $tableName, array $entity)
    {
        $this->validateTableName($tableName);

        $fields = [];
        $tokens= [];
        $params = [];
        foreach ($entity as $key => $value) {
            if (in_array($key, $this->tables[$tableName])) {
                $fields[] = $key;
                $tokens[] = ":$key";
                $params[":$key"] = $value;
            }
        }
        if (empty($fields)) {
            throw new InvalidArgumentException("No matching columns in table $tableName");
        } else {
            $query = "INSERT INTO $tableName (" . implode(', ', $fields) . ') VALUES (' . implode(', ', $tokens) . ')';

            return $this->insert($query, $params);
        }
    }

    public function updateOne(string $tableName, array $entity, $primaryKey)
    {
        $this->validateTableName($tableName);

        $fields = [];
        $params = [];
        foreach ($entity as $key => $value) {
            if (in_array($key, $this->tables[$tableName])) {
                $fields[] = "$key = :$key";
                $params[":$key"] = $value;
            }
        }
        $params[":{$this->primaryKeys[$tableName]}"] = $primaryKey;
        if (empty($fields)) {
            throw new InvalidArgumentException("No matching columns in table $tableName");
        } else {
            $query = "UPDATE $tableName SET " . implode(', ', $fields) . " WHERE {$this->primaryKeys[$tableName]} = :{$this->primaryKeys[$tableName]}";

            return $this->insert($query, $params);
        }
    }

    public function getAll(string $tableName, string $orderBy = 'name ASC')
    {
        $this->validateTableName($tableName);
        $query = "SELECT * FROM $tableName";
        if ($orderBy) {
            $query .= " ORDER BY $orderBy";
        }

        return $this->select($query);
    }

    public function getOneById(string $tableName, $id)
    {
        $this->validateTableName($tableName);

        return $this->selectOne("SELECT * FROM $tableName WHERE {$this->primaryKeys[$tableName]} = :{$this->primaryKeys[$tableName]}", array(":{$this->primaryKeys[$tableName]}" => $id));
    }

    public function deleteOneById(string $tableName, $id)
    {
        $this->validateTableName($tableName);

        return $this->update("DELETE FROM $tableName WHERE {$this->primaryKeys[$tableName]} = :{$this->primaryKeys[$tableName]} LIMIT 1", array(":{$this->primaryKeys[$tableName]}" => $id));
    }

    public function existsById(string $tableName, $id)
    {
        return $this->selectOneValue("SELECT EXISTS(SELECT * FROM {$tableName} WHERE {$this->primaryKeys[$tableName]} = :id LIMIT 1) AS e", [':id' => $id]);
    }



    protected function countQuery()
    {
        $this->numQueries++;
    }

    public function getNumQueries()
    {
        return $this->numQueries;
    }

    public function select(string $query, array $params = [])
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute($this->tokenizeParams($params));
        $this->countQuery();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectOne(string $query, array $params = [])
    {
        $result = $this->select($query, $params);
        if (count($result) > 1) {
            Throw new Exception('Database returned more than one result');
        }
        
        return array_shift($result);
    }
    
    public function selectOneField(string $query, array $params = [])
    {
        $array = [];
        $result = $this->select($query, $params);
        if (count($result)) {
            foreach ($result as $row) {
                if (count($row) > 1) {
                    Throw new Exception('Your query selected more than one field');
                } else {
                    foreach ($row as $value) {
                        $array[] = $value;
                    }
                }
            }
        }
        return $array;
    }

    public function selectOneValue(string $query, array $params = [])
    {
        $result = $this->selectOne($query, $params);
        if (count($result) != 1) {
            Throw new ResultException('Database returned more than one value');
        }
        return array_shift($result);
    }

    public function insert(string $query, array $params = [])
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute($this->tokenizeParams($params));
        $this->countQuery();

        return $this->db->lastInsertId();
    }


    public function update(string $query, array $params = [])
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute($this->tokenizeParams($params));
        $this->countQuery();

        return $stmt->rowCount();
    }

    public function delete(string $query, array $params = [])
    {
        return $this->update($query, $this->tokenizeParams($params));
    }

    public function command(string $query)
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $this->countQuery();

        return true;
    }


    private function validateTableName($tableName)
    {
        if (!array_key_exists($tableName, $this->tables)) {
            Throw new InvalidArgumentException("Table '$tableName' does not exist");
        }
    }
    
    private function tokenizeParams(array $params = [])
    {   
        $tokenizedParams = [];
        if (count($params)) {
            foreach ($params as $field => $value) {
                $tokenizedParams[':' . ltrim($field, ':')] = $value;
            }
        }
        
        return $tokenizedParams;
    }
}
