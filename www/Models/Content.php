<?php
namespace App\Models;

use App\Core\SQL;

class Content extends SQL
{
    private int $id = 0;
    protected string $path_content;

    public function __construct(){
        parent::__construct();
    }

    /**
     * @return Int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param Int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getPathContent(): string
    {
        return $this->path_content;
    }

    /**
     * @param string $path_content
     */
    public function setPathContent(string $path_content): void
    {
        $this->path_content = $path_content;
    }

}