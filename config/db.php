<?php

// Adatbázis kapcsolat beállításai.
$host = "localhost";
$dbname = "projectboard";
$username = "root";
$password = "";

try {

    // PDO kapcsolat létrehozása.
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // Hibák kezelése kivételekkel.
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Az adatok asszociatív tömbként térjenek vissza.
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {

    die("Adatbázis kapcsolódási hiba: " . $e->getMessage());

}