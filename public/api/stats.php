<?php

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../models/Project.php";

// A válasz JSON formátumú.
header("Content-Type: application/json; charset=utf-8");

$projectModel = new Project($pdo);

// Dashboard statisztikák összeállítása.
$data = [
    "totalProjects" => $projectModel->getProjectCount(),
    "personalProjects" => $projectModel->getPersonalProjectCount(),
    "clientProjects" => $projectModel->getClientProjectCount(),
    "activeProjects" => $projectModel->getActiveProjectCount()
];

// JSON válasz küldése.
echo json_encode([
    "success" => true,
    "data" => $data
], JSON_UNESCAPED_UNICODE);