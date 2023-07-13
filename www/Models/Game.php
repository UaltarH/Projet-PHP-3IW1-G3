<?php

namespace App\Models;

class Game extends AbstractModel
{
    private string $id = "0";
    protected string $title_game;
    protected string $category_id;

    public function __construct(){
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
        return $this->title_game;
    }

    /**
     * @param String $title
     */
    public function setTitle(string $title_game): void
    {
        $this->title_game = $title_game;
    }

    /**
     * @return string
     */
    public function getCategory_id(): string
    {
        return $this->category_id;
    }

    /**
    * @param string $category_id
    */
    public function setCategory_id(string $category_id): void
    {
        $this->category_id = $category_id;
    }
}

