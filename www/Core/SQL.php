<?php

namespace App\Core;

// TODO : @Mathvss move this
class ResponseSave
{
    public bool $success;
    public string|int|null $idNewElement;
}

class SQL
{
    private static $instance;
    private static \PDO $connection;

    protected function __construct()
    {
        try {
            $config = Config::getInstance()->getConfig();
            $bdd = $config['bdd'];
            self::$connection = new \PDO("pgsql:host=".$bdd['host'].";dbname=".$bdd['dbname'].";port=".$bdd['port'], $bdd['username'], $bdd['password']);
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

    public static function getConnection(): \PDO
    {
        return self::$connection;
    }
}