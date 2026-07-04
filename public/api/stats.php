<?php

require_once __DIR__ . "/../../config/db.php";
require_once __DIR__ . "/../../models/Project.php";

header("Content-Type: application/json; charset=utf-8");

$projectModel = new Project($pdo);

echo json_encode([
    "success" => true,
    "data" => [
        "totalProjects" => $projectModel->getProjectCount(),
        "personalProjects" => $projectModel->getPersonalProjectCount(),
        "clientProjects" => $projectModel->getClientProjectCount(),
        "activeProjects" => $projectModel->getActiveProjectCount()
    ]
], JSON_UNESCAPED_UNICODE);