<?php

class Auth
{
    // A PDO adatbázis-kapcsolat tárolása.
    private PDO $pdo;

    // Az adatbázis-kapcsolat átvétele és eltárolása.
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Admin felhasználó hitelesítése.
    public function login(string $username, string $password): array|false
    {
        // Az admin felhasználó lekérése felhasználónév alapján.
        $sql = "SELECT *
            FROM admins
            WHERE username = :username";

        // Az SQL-lekérdezés előkészítése.
        $stmt = $this->pdo->prepare($sql);

        // A lekérdezés végrehajtása a megadott felhasználónévvel.
        $stmt->execute([
            "username" => $username
        ]);

        // A megtalált admin adatainak lekérése.
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ha nincs ilyen felhasználó, a hitelesítés sikertelen.
        if (!$admin) {
            return false;
        }

        // A megadott jelszó ellenőrzése a tárolt jelszóhash alapján.
        if (!password_verify($password, $admin["password"])) {
            return false;
        }

        // Sikeres hitelesítés esetén az admin adatainak visszaadása.
        return $admin;
    }
}
