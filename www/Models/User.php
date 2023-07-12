<?php

namespace App\Models;

class User extends AbstractModel
{
    private string $id = "0";
    protected string $pseudo;
    protected string $first_name;
    protected string $last_name;
    protected string $email;
    protected string $password;
    protected bool $email_confirmation;
    protected int $phone_number;
    protected string $date_inscription;
    protected string $role_id ;
    protected ?string $confirm_and_reset_token;

    public function __construct()
    {
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
        $this->id = strtolower(trim($id));
    }

    /**
     * @return String
     */
    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    /**
     * @param String $pseudo
     */
    public function setPseudo(string $pseudo): void
    {
        $this->pseudo = trim($pseudo);
    }

    /**
     * @return String
     */
    public function getFirstname(): string
    {
        return $this->first_name;
    }

    /**
     * @param String $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->first_name = ucwords(strtolower(trim($firstname)));
    }

    /**
     * @return String
     */
    public function getLastname(): string
    {
        return $this->last_name;
    }

    /**
     * @param String $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->last_name = strtoupper(trim($lastname));
    }

    /**
     * @return String
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param String $email
     */
    public function setEmail(string $email): void
    {
        $this->email = strtolower(trim($email));
    }

    /**
     * @return String
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param String $password
     */
    public function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }


    public function getEmailConfirmation(): bool
    {
        return $this->email_confirmation;
    }


    public function setEmailConfirmation(bool $emailConfirmation): void
    {
        $this->email_confirmation = $emailConfirmation;
    }

    /**
     * @return Int
     */
    public function getPhoneNumber(): int
    {
        return $this->phone_number;
    }

    /**
     * @param int $phoneNumber
     */
    public function setPhoneNumber(int $phoneNumber): void
    {
        $this->phone_number = $phoneNumber;
    }

    /**
     * @return String
     */
    public function getDateInscription(): string
    {
        return $this->date_inscription;
    }

    /**
     * @param string $dateInscription
     */
    public function setDateInscription(string $dateInscription): void
    {
        $this->date_inscription = $dateInscription;
    }

    /**
     * @return string
     */
    public function getRoleId(): string
    {
        return $this->role_id;
    }

    /**
     * @param string $roleId
     */
    public function setRoleId(string $roleId): void
    {
        $this->role_id = $roleId;
    }

    /**
     * @return String
     */
    public function getConfirmAndResetToken(): string
    {
        return empty($this->confirm_and_reset_token) ? '' : $this->confirm_and_reset_token;
    }

    /**
     * @param string $confirmAndResetToken
     */
    public function setConfirmAndResetToken(string $confirmAndResetToken): void
    {
        $this->confirm_and_reset_token = $confirmAndResetToken;
    }

}
