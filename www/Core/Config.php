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
}