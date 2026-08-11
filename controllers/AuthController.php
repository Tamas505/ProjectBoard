<?php

// Az Auth modell betöltése.
require_once __DIR__ . "/../models/Auth.php";

class AuthController
{
    // Az Auth modell objektumának tárolása.
    private Auth $authModel;

    // Az adatbázis-kapcsolat átadása az Auth modellnek.
    public function __construct(PDO $pdo)
    {
        $this->authModel = new Auth($pdo);
    }

    // Admin bejelentkezés kezelése.
    public function login(): void
    {
        // A validációs hibák tárolása.
        $errors = [];

        // Az űrlap feldolgozása POST kérés esetén.
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // A felhasználónév és jelszó kiolvasása az űrlapból.
            $username = trim($_POST["username"] ?? "");
            $password = $_POST["password"] ?? "";

            // A kötelező mezők ellenőrzése.
            if ($username === "" || $password === "") {
                $errors[] = "A felhasználónév és a jelszó megadása kötelező.";
            } else {

                // A megadott adatok hitelesítése az Auth modellen keresztül.
                $admin = $this->authModel->login($username, $password);

                // Sikeres hitelesítés esetén a bejelentkezési adatok mentése a sessionbe.
                if ($admin) {
                    $_SESSION["admin_id"] = $admin["id"];
                    $_SESSION["admin_username"] = $admin["username"];

                    // Átirányítás a főoldalra.
                    header("Location: index.php");
                    exit;
                }

                // Sikertelen hitelesítés esetén hibaüzenet.
                $errors[] = "Hibás felhasználónév vagy jelszó.";
            }
        }

        // A bejelentkezési nézet betöltése.
        require_once __DIR__ . "/../views/auth/login.php";
    }

    // Admin kijelentkezés, session törlése.
    public function logout(): void
    {
        // A sessionben tárolt adatok kiürítése.
        $_SESSION = [];

        // Ha a session sütit használ, a session cookie törlése.
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                "",
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // A session teljes megszüntetése.
        session_destroy();

        // Visszairányítás a bejelentkezési oldalra.
        header("Location: index.php?action=login");
        exit;
    }
}