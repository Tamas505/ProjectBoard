<?php

/** @var array<string, mixed> $project */
/** @var array $versions */
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($project["title"]) ?> - ProjectBoard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light text-dark">

    <div class="container py-4">

        <a href="index.php" class="btn btn-secondary mb-3">
            Vissza a projektekhez
        </a>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h1 class="h3">
                    <?= htmlspecialchars($project["title"]) ?>
                </h1>

                <p>
                    <?= htmlspecialchars($project["description"]) ?>
                </p>

                <?php if (!empty($project["notes"])): ?>
                    <div class="alert alert-warning">
                        <strong>Fejlesztői megjegyzések:</strong><br>

                        <?= nl2br(htmlspecialchars($project["notes"])) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($project["github_url"])): ?>
                    <p>
                        <strong>GitHub:</strong>
                        <a href="<?= htmlspecialchars($project["github_url"]) ?>" target="_blank">
                            <?= htmlspecialchars($project["github_url"]) ?>
                        </a>
                    </p>
                <?php endif; ?>

                <?php if (!empty($project["live_url"])): ?>
                    <p>
                        <strong>Élő oldal:</strong>
                        <a href="<?= htmlspecialchars($project["live_url"]) ?>" target="_blank">
                            <?= htmlspecialchars($project["live_url"]) ?>
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <h2 class="h4 mb-3">Verziónapló</h2>
        <a
            href="index.php?action=createVersion&project_id=<?= $project['id'] ?>"
            class="btn btn-primary mb-3">
            Új verzió hozzáadása
        </a>

        <?php if (empty($versions)): ?>
            <div class="alert alert-info">
                Ehhez a projekthez még nincs verzióbejegyzés.
            </div>
        <?php endif; ?>

        <?php foreach ($versions as $version): ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <h3 class="h5">
                        <?= htmlspecialchars($version["version_number"]) ?>
                    </h3>

                    <?php
                    $typeClass = match ($version["change_type"]) {
                        "feature" => "bg-primary",
                        "bugfix" => "bg-danger",
                        "design" => "bg-info",
                        "content" => "bg-warning",
                        "release" => "bg-success",
                        default => "bg-secondary"
                    };

                    $typeText = match ($version["change_type"]) {
                        "feature" => "Új funkció",
                        "bugfix" => "Hibajavítás",
                        "design" => "Design",
                        "content" => "Tartalom",
                        "release" => "Kiadás",
                        default => $version["change_type"]
                    };
                    ?>

                    <p class="mb-1">
                        <strong>Típus:</strong>

                        <span class="badge <?= $typeClass ?>">
                            <?= htmlspecialchars($typeText) ?>
                        </span>
                    </p>

                    <p>
                        <?= htmlspecialchars($version["description"]) ?>
                    </p>

                    <span class="badge <?= $version["deployed"] ? "bg-success" : "bg-warning" ?>">
                        <?= $version["deployed"] ? "Élesítve" : "Nincs élesítve" ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

</body>

</html>