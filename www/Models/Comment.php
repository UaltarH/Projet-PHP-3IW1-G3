<?php

namespace App\Models;

use App\Core\SQL;

class Comment extends SQL
{
    private $db_connexion;
    private string $id = "0";
    protected string $content;
    protected string $creation_date;
    protected bool $moderated;
    protected bool $accepted;
    protected string $user_id;

    public function __construct()
    {
        $this->db_connexion = SQL::getInstance()->getConnection();
    }

    public static function getTable(): string
    {
        $classExploded = explode("\\", get_called_class());
        return "carte_chance_" . strtolower(end($classExploded));
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
    public function getCreationDate(): string
    {
        return $this->creation_date;
    }

    /**
     * @param string $creation_date
     */
    public function setCreationDate(string $creation_date): void
    {
        $this->creation_date = $creation_date;
    }

    /**
     * @return bool
     */
    public function isModerated(): bool
    {
        return $this->moderated;
    }

    /**
     * @param bool $moderated
     */
    public function setModerated(bool $moderated): void
    {
        $this->moderated = $moderated;
    }

    /**
     * @return bool
     */
    public function isAccepted(): bool
    {
        return $this->accepted;
    }

    /**
     * @param bool $accepted
     */
    public function setAccepted(bool $accepted): void
    {
        $this->accepted = $accepted;
    }

    /**
     * @return bool
     */
    public function isUserId(): bool
    {
        return $this->user_id;
    }

    /**
     * @param bool $user_id
     */
    public function setUserId(bool $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }
}