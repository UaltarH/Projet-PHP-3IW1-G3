<?php

namespace  App\Core;

define("APPLICATION_PATH", "application.yml");
define("APPLICATION_DEV_PATH", "application-dev.yml");
define("APPLICATION_PROD_PATH", "application-prod.yml");
class Config
{
    private static ?Config $instance = null;
    private $config;
    private string $environment;

    private function __construct()
    {
        if(!file_exists(APPLICATION_PATH)) {
            Errors:define(500, "Le fichier application.yml n'existe pas");
            exit;
        }
         $this->setEnvironment(yaml_parse_file(APPLICATION_PATH)["environment"]);
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }
    public function setEnvironment($environment): void
    {
        $this->environment = strtolower(trim($environment));
    }
    public static function getInstance(): Config
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function getConfig() {

    }
}