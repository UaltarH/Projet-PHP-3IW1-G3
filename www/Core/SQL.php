<?php

namespace App\Core;

class ResponseSave
{
    public bool $success;
    public string|int|null $idNewElement;
}

class SQL
{
    private static $instance;
    private static $connection;

    protected function __construct()
    {
        try {
            self::$connection = new \PDO("pgsql:host=database;dbname=esgi;port=5432", "esgi", "Test1234");
        } catch (\Exception $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    }

    public static function getInstance()
    {

        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);

        if (isset($trace[1]['class'])) {
            if (is_null(self::$instance)) {
                self::$instance = new SQL($trace[1]['class']);
            }

            return self::$instance;
        }
        die("Pas de class d'appel");
    }

    public function getConnection()
    {
        return self::$connection;
    }

}