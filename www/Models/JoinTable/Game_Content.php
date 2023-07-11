<?php

namespace App\Models\JoinTable;

use App\Core\SQL;

class Game_Content
{
    protected string $jeux_id;
    protected string $content_id;

    public function __construct(){
    }

    /**
     * @return string
     */
    public function getJeuId(): string
    {
        return $this->jeux_id;
    }

    /**
    * @param string $jeux_id
    */
    public function setJeuId(string $jeux_id): void
    {
        $this->jeux_id = $jeux_id;
    }

    /**
     * @return string
     */
    public function getContentId(): string
    {
        return $this->content_id;
    }

    /**
    * @param string $content_id
    */
    public function setContentId(string $content_id): void
    {
        $this->content_id = $content_id;
    }
}