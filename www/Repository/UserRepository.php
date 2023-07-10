<?php
namespace App\Repository;
use App\Core\SQL;
use App\Models\User;
use DateInterval;
use DatePeriod;
use DateTime;
use PDO;

/**
 * Classe qui regroupe les méthodes liées au model User
 */
class UserRepository extends AbstractRepository
{
    /**
     * @return array
     */
    public static function fetchUserRole(): array
    {
        $query = SQL::getInstance()->getConnection()->query("SELECT id FROM carte_chance_role WHERE role_name='user'");
        return $query->fetch();
    }

    /**
     * @return array
     */
    public static function fetchRoles(): array
    {
        $query = SQL::getInstance()->getConnection()->query("SELECT * FROM carte_chance_role");
        return $query->fetchAll();
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function getNewUsersPerDay(): array
    {
        $dateDebut = date('Y-m-d', strtotime('-1 month'));
        $dateFin = date('Y-m-d', strtotime('+1 day'));
        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod(new DateTime($dateDebut), $interval, new DateTime($dateFin));

        $newUsersPerDay = [];

        foreach ($dateRange as $date) {
            $dateCourante = $date->format('Y-m-d');

            $requete = SQL::getInstance()->getConnection()->prepare("SELECT COUNT(*) AS count FROM carte_chance_user WHERE DATE(date_inscription) = ?");
            $requete->execute([$dateCourante]);
            $resultat = $requete->fetch(PDO::FETCH_ASSOC);

            $newUsersPerDay[] = [
                'date' => $dateCourante,
                'count' => (int)$resultat['count']
            ];
        }
        return $newUsersPerDay;
    }

    /**
     * Crée 100 utilisateurs, ne marche qu'une fois
     * @return void
     */
    public static function userFaker(): void
    {
        $query = "INSERT INTO carte_chance_user (pseudo, first_name, last_name, email, password, email_confirmation, phone_number, date_inscription, role_id) VALUES";
        for ($i = 0; $i < 100; $i++) {
            $query .= "('pseudo$i', 'firstname$i', 'lastname$i', 'email$i@email.com', 'Test$i', true, '0123456" . str_pad($i, 3, "0", STR_PAD_LEFT) . "', '" . date("Y-m-d H:i:s") . "', 1)";
            if ($i !== 99) $query .= ",";
        }
        $query .= ";";
        $queryPrepared = SQL::getInstance()->getConnection()->prepare($query);
        $queryPrepared->execute();
    }
}