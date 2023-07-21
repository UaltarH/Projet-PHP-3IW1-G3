<?php

namespace  App\Core;

use App\Core\Errors;

define("APPLICATION_PATH", "../application.yml");
define("APPLICATION_DEV_PATH", "../application-dev.yml");
define("APPLICATION_PROD_PATH", "../application-prod.yml");
class Config
{
    private static ?Config $instance = null;
    private static ?array $config = [];
    private static string $environment;

    private function __construct()
    {
        if(!file_exists(APPLICATION_PATH)) {
            Errors::define(500, "Le fichier {${APPLICATION_PATH}} n'existe pas");
            exit;
        }
        $this->setEnvironment(yaml_parse_file(APPLICATION_PATH)["environment"]);
        $configFileName = constant('APPLICATION_'.$this->getEnvironment().'_PATH');
        if(!file_exists($configFileName)) {
            Errors::define(500, "Le fichier {${APPLICATION_PATH}} n'existe pas");
            exit;
        }
        self::setConfig($configFileName);
    }

    public function setEnvironmentVariable($keyVariable, $valueVariable){
        $configFileName = constant('APPLICATION_'.$this->getEnvironment().'_PATH');
        $config = self::getConfig();
        $config[$keyVariable] = $valueVariable;
        $yaml = yaml_emit($config);
        file_put_contents($configFileName, $yaml);
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return self::$environment;
    }
    /**
     * @param $environment
     * @return void
     */
    public function setEnvironment($environment): void
    {
        self::$environment = strtoupper(trim($environment));
    }

    /**
     * @return Config
     */
    public static function getInstance(): Config
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /***
     * @param $file
     * @return void
     */
    private function setConfig($file): void
    {
        self::$config = yaml_parse_file($file);
    }
    /**
     * @return array|null
     */
    public static function getConfig(): ?array
    {
        if(self::$config === []) {
            self::getInstance()->getConfig();
        }
        return self::$config;
    }

    /**
     * Modifies la valeur d'une clé dans le fichier de config
     * Parcours les clés passées en paramètre et modifie la valeur de la dernière clé
     * @param $keys : Tableau de clé
     * @param $newValue
     * @return bool
     */
    public function updateConfig($keys, $newValue): bool
    {
        $configFileName = constant('APPLICATION_'. self::getInstance()->getEnvironment().'_PATH');
        $data = self::getConfig();
        $currentData = &$data;
        foreach ($keys as $key) {
                if (!isset($currentData[$key])) {
                    Errors::define(404, "Route not exist");
                    exit;
                }
                $currentData = &$currentData[$key];
        }
        $currentData = $newValue;
        $yaml = yaml_emit($data);
        $result = file_put_contents($configFileName, $yaml);
        $this->setConfig($configFileName);
        return $result !== false;
    }
}