<?php

// Az adatbázis-kapcsolat és a Project modell betöltése.
require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../models/Project.php";

// A válasz JSON formátumának beállítása.
header("Content-Type: application/json; charset=utf-8");

// A Project modell példányosítása az adatbázis-kapcsolattal.
$projectModel = new Project($pdo);

// Dashboard statisztikák lekérése és összeállítása.
$data = [
    "totalProjects" => $projectModel->getProjectCount(),
    "personalProjects" => $projectModel->getPersonalProjectCount(),
    "clientProjects" => $projectModel->getClientProjectCount(),
    "activeProjects" => $projectModel->getActiveProjectCount()
];

// Sikeres JSON válasz összeállítása és elküldése.
echo json_encode([
    "success" => true,
    "data" => $data
], JSON_UNESCAPED_UNICODE);
