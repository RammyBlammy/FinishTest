<?php

namespace TestTask\Helpers;

use Exception;
use PDO;
use PDOException;

class DB
{
    private PDO $pdo;
    private static DB $instance;
    private function __construct()
    {
        $dbhost = getenv("DB_HOST");
        $dbport = getenv("DB_PORT");
        $dbname = getenv("DB_NAME");
        $dbuser = getenv("DB_USER");
        $dbpass = getenv("DB_PASS");

        try {
            $this->pdo = new \PDO("pgsql:host=$dbhost;port=$dbport;dbname=$dbname", $dbuser, $dbpass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $exception) {
            throw new Exception("Ошибка при подключении к базе данных<br><b>{$exception->getMessage()}</b><br>");
        }
    }
    // передается название вызываемой функции и ее параметры
    static public function __callStatic($name, $arguments): mixed
    {
        if (!isset($instance))
            static::$instance = new DB();
        if (method_exists(static::$instance, $name))
            return static::$instance->$name(...$arguments);
        else return null;
    }
    private function select(string $table, array $fields, array $joins, array $where): array
    {
        $str_fields = implode(",", $fields);

        $joins_string = "";
        foreach ($joins as $type => $table_conditions) {
            foreach ($table_conditions as $table_name => $value) {
                foreach ($value as $column1 => $column2) {
                    $joins_string .= "$type $table_name ON $column1=$column2 ";
                }
            }
        }

        foreach ($where as $wh) {
            if (count($wh) == 3) {
                $str_where[] = $wh[0] . " " . $wh[1] . " " . $wh[2];
            } else if (count($wh) == 2) {
                $str_where[] = $wh[0] . " = " . $wh[1];
            } else {
                throw new Exception("))");
            }
        }
        if (count($str_where) > 0)
            $str_where = implode(" AND ", $str_where);
        $sql = "SELECT $str_fields FROM $table $joins_string WHERE $str_where";
        return $this->query($sql);
    }
    private function insert(string $table, array $values): void
    {
        $keys = [];
        foreach ($values as $value) {
            $keys = array_merge(array_keys($value), $keys);
        }
        $keys = array_unique($keys);
        $values2 = [];
        foreach ($values as $key => $value) {
            $rows_keys = [];
            foreach ($keys as $k) {
                $values2[$k . $key] = $value[$k] ?? null;
                $rows_keys[] = ":" . $k . $key;
            }
            $rows[] = "(" . implode(",", $rows_keys) . ")";
        }
        $str_values = implode(",", $rows);
        foreach ($keys as $key) {
            $str[] = "\"$key\"";
        }
        $str = implode(",", $keys);
        $sql = "INSERT INTO $table ($str) VALUES $str_values";
        $this->query($sql, $values2);
    }
    private function query(string $query, array $params = []): ?array
    {
        $ex = $this->pdo->prepare($query);
        $ex->execute($params);
        return $ex->fetchAll(PDO::FETCH_ASSOC);
    }
}
