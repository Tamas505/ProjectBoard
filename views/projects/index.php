<?php

/** @var array $projects */
/** @var int $projectCount */
/** @var int $personalProjectCount */
/** @var int $clientProjectCount */
/** @var int $activeProjectCount */

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>ProjectBoard - Projects</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/ProjectBoard/public/css/style.css">

</head>

<body class="bg-light text-dark">

    <div class="container py-4">
        <h1 class="mb-4 text-center">ProjectBoard - Projektek</h1>
        <div class="mb-4">
            <a href="index.php?action=create" class="btn btn-primary">
                Új projekt hozzáadása
            </a>

            <a href="index.php?action=logout" class="btn btn-secondary">
                Kijelentkezés
            </a>
        </div>
        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 dashboard-total">
                    <div class="card-body">
                        <div class="text-muted">Összes projekt</div>

                        <div class="display-6 fw-bold">
                            <span id="totalProjects">...</span>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 dashboard-personal">
                    <div class="card-body">
                        <div class="text-muted">Saját projektek</div>

                        <div class="display-6 fw-bold text-info">
                            <span id="personalProjects">...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 dashboard-client">
                    <div class="card-body">
                        <div class="text-muted">Ügyfél projektek</div>

                        <div class="display-6 fw-bold text-primary">
                            <span id="clientProjects">...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm h-100 dashboard-active">
                    <div class="card-body">
                        <div class="text-muted">Aktív projektek</div>

                        <div class="display-6 fw-bold text-success">
                            <span id="activeProjects">...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <?php foreach ($projects as $project): ?>
                <div class="col-md-6">

                    <div class="card shadow-sm border-0 h-100 project-card <?= htmlspecialchars($project["project_type"]) ?>">
                        <div class="card-body">

                            <h2 class="h5">
                                <?= htmlspecialchars($project["title"]) ?>
                            </h2>

                            <p>
                                <?= htmlspecialchars($project["description"]) ?>
                            </p>

                            <p class="mb-0">
                                <strong>Típus:</strong>

                                <span class="badge <?= $project["project_type"] === "personal" ? "bg-info" : "bg-primary" ?>">
                                    <?= $project["project_type"] === "personal" ? "Saját projekt" : "Ügyfélprojekt" ?>
                                </span>
                            </p>

                            <?php
                            $statusClass = match ($project["status"]) {
                                "planning" => "bg-warning",
                                "active" => "bg-primary",
                                "completed" => "bg-success",
                                "cancelled" => "bg-danger",
                                default => "bg-secondary"
                            };

                            $statusText = match ($project["status"]) {
                                "planning" => "Tervezés",
                                "active" => "Aktív",
                                "completed" => "Kész",
                                "cancelled" => "Törölve",
                                default => $project["status"]
                            };
                            ?>

                            <p class="mt-2">
                                <strong>Státusz:</strong>

                                <span class="badge <?= $statusClass ?>">
                                    <?= htmlspecialchars($statusText) ?>
                                </span>
                            </p>

                            <p class="mt-2 mb-0">
                                <strong>Verzió:</strong>

                                <span class="badge bg-dark">
                                    <?= htmlspecialchars($project["latest_version"]) ?>
                                </span>
                            </p>

                            <a
                                href="index.php?action=delete&id=<?= $project['id'] ?>"
                                class="btn btn-danger btn-sm mt-3"
                                onclick="return confirm('Biztosan törlöd ezt a projektet?')">
                                Törlés
                            </a>

                            <a
                                href="index.php?action=edit&id=<?= $project['id'] ?>"
                                class="btn btn-warning btn-sm mt-3">
                                Módosítás
                            </a>
                            <a
                                href="index.php?action=show&id=<?= $project['id'] ?>"
                                class="btn btn-info btn-sm mt-3">
                                Részletek
                            </a>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <script>
                fetch("api/stats.php")
                    .then(response => response.json())
                    .then(result => {

                        if (!result.success) {
                            console.log("API hiba");
                            return;
                        }

                        document.getElementById("totalProjects").textContent =
                            result.data.totalProjects;

                        document.getElementById("personalProjects").textContent =
                            result.data.personalProjects;

                        document.getElementById("clientProjects").textContent =
                            result.data.clientProjects;

                        document.getElementById("activeProjects").textContent =
                            result.data.activeProjects;
                    })
                    .catch(error => {
                        console.log("Kapcsolódási hiba:", error);
                    });
            </script>
</body>

</html>