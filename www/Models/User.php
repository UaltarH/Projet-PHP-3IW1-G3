<?php

namespace App\Models;

use App\Core\SQL;
use DateInterval;
use DatePeriod;
use DateTime;
use PDO;

class User extends SQL
{
    private $db_connexion;
    private int $id = 0;
    protected string $pseudo;
    protected string $first_name;
    protected string $last_name;
    protected string $email;
    protected string $password;
    protected bool $email_confirmation = false;
    protected int $phone_number;
    protected string $date_inscription;
    protected int $role_id = 1; // 1 represente un utilisateur normal ; 2 represente un admin
    protected string $confirmToken;


    public function __construct()
    {
        //de base 
        // parent::__construct();

        $this->db_connexion = SQL::getInstance()->getConnection();
    }

    public static function getTable(): string
    {
        $classExploded = explode("\\", get_called_class());
        return "carte_chance_" . strtolower(end($classExploded));
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
     * @param Int $phone_number
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
     * @param String $date_inscription
     */
    public function setDateInscription(string $dateInscription): void
    {
        $this->date_inscription = $dateInscription;
    }

    /**
     * @return Int
     */
    public function getRoleId(): int
    {
        return $this->role_id;
    }

    /**
     * @param Int $role_id
     */
    public function setRoleId(int $roleId): void
    {
        $this->role_id = $roleId;
    }

    /**
     * @return String
     */
    public function getConfirmToken(): string
    {
        return $this->confirmToken;
    }

    /**
     * @param String $confirmToken
     */
    public function setConfirmToken(string $confirmToken): void
    {
        $this->confirmToken = $confirmToken;
    }

    public function userFaker(): string
    {
        $query = "INSERT INTO carte_chance_user (pseudo, first_name, last_name, email, password, email_confirmation, phone_number, date_inscription, role_id) VALUES";
        for ($i = 0; $i < 100; $i++) {
            $query .= "('pseudo$i', 'firstname$i', 'lastname$i', 'email$i@email.com', 'Test$i', true, '$i', '" . date("Y-m-d H:i:s") . "', 1)";
            if ($i !== 99) $query .= ",";
        }
        $query .= ";";
        return $query;
    }

    public function getTotalUsers(): int
    {
        $queryPrepared = $this->db_connexion->prepare("SELECT COUNT(*) FROM carte_chance_user");
        $queryPrepared->execute();
        return $queryPrepared->fetch()['count'];
    }

    public function getNewUsersPerDay(): array
    {
        $dateDebut = date('Y-m-d', strtotime('-1 month'));
        $dateFin = date('Y-m-d', strtotime('+1 day'));
        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod(new DateTime($dateDebut), $interval, new DateTime($dateFin));

        $newUsersPerDay = [];

        foreach ($dateRange as $date) {
            $dateCourante = $date->format('Y-m-d');

            $requete = $this->db_connexion->prepare("SELECT COUNT(*) AS count FROM carte_chance_user WHERE DATE(date_inscription) = ?");
            $requete->execute([$dateCourante]);
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);

            $newUsersPerDay[] = [
                'date' => $dateCourante,
                'count' => (int)$resultat['count']
            ];
        }
        return $newUsersPerDay;
    }
}
