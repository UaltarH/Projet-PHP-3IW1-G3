<?php
namespace App\Models;

use App\Core\SQL;

class Role
{
    private string $id = "0";
    protected string $role_name;

    public function __construct(){
    }

    public static function getTable(): string
    {
        $classExploded = explode("\\", get_called_class());
        return  "carte_chance_".strtolower(end($classExploded));
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getRoleName(): string
    {
        return $this->role_name;
    }
    
    /**
     * @param string $role_name
     */
    public function setRoleName(string $role_name): void
    {
        $this->role_name = $role_name;
    }

}