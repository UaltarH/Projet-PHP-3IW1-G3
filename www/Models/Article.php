<?php

namespace App\Models;

use App\Core\SQL;

class Article extends SQL
{
    private $db_connexion;
    private string $id = "0";
    protected string $content;
    protected string $title;
    protected string $created_date;
    protected string $updated_date;
    protected string $category_id;

    public function __construct(){
        $this->db_connexion = SQL::getInstance()->getConnection();
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
    * @return String
    */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param String $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
    * @return String
    */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param String $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
    * @return String
    */
    public function getCreatedDate(): string
    {
        return $this->created_date;
    }

    /**
     * @param String $content
     */
    public function setCreatedDate(string $created_date): void
    {
        $this->created_date = $created_date;
    }

    /**
    * @return String
    */
    public function getUpdatedDate(): string
    {
        return $this->updated_date;
    }

    /**
     * @param String $content
     */
    public function setUpdatedDate(string $updated_date): void
    {
        $this->updated_date = $updated_date;
    }

    /**
     * @return string
     */
    public function getCategoryId(): string
    {
        return $this->category_id;
    }

    /**
    * @param string $id
    */
    public function setCategoryId(string $category_id): void
    {
        $this->category_id = $category_id;
    }
}