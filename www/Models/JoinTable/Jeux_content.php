<?php

namespace App\Models\JoinTable;

use App\Core\SQL;

class Jeux_content extends SQL
{
    protected int $jeux_id;
    protected int $content_id;

    public function __construct(){
        parent::__construct();
    }

    /**
     * @return Int
     */
    public function getJeuId(): int
    {
        return $this->jeux_id;
    }

    /**
    * @param Int $jeux_id
    */
    public function setJeuId(int $jeux_id): void
    {
        $this->jeux_id = $jeux_id;
    }

    /**
     * @return Int
     */
    public function getContentId(): int
    {
        return $this->content_id;
    }

    /**
    * @param Int $content_id
    */
    public function setContentId(int $content_id): void
    {
        $this->content_id = $content_id;
    }
}