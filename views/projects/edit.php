<?php

/** @var array<string, mixed> $project */
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>Projekt módosítása - ProjectBoard</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light text-dark">

    <div class="container py-4">
        <h1 class="mb-4">Projekt módosítása</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label class="form-label">Projekt neve</label>
            <input
                type="text"
                name="title"
                class="form-control mb-3"
                value="<?= htmlspecialchars($project["title"]) ?>">

            <label class="form-label">Projekt leírása</label>

            <textarea
                name="description"
                class="form-control mb-3"><?= htmlspecialchars($project["description"]) ?>
            </textarea>

            <label class="form-label">Fejlesztői megjegyzések</label>
            <textarea
                name="notes"
                class="form-control mb-3"
                rows="4"><?= htmlspecialchars($project["notes"] ?? "") ?></textarea>

            <select name="project_type" class="form-select mb-3">
                <option value="personal" <?= $project["project_type"] === "personal" ? "selected" : "" ?>>
                    Saját projekt
                </option>
                <option value="client" <?= $project["project_type"] === "client" ? "selected" : "" ?>>
                    Ügyfélprojekt
                </option>
            </select>

            <select name="status" class="form-select mb-3">

                <option value="planning"
                    <?= $project["status"] === "planning" ? "selected" : "" ?>>
                    Tervezés
                </option>

                <option value="active"
                    <?= $project["status"] === "active" ? "selected" : "" ?>>
                    Aktív
                </option>

                <option value="completed"
                    <?= $project["status"] === "completed" ? "selected" : "" ?>>
                    Kész
                </option>

                <option value="cancelled"
                    <?= $project["status"] === "cancelled" ? "selected" : "" ?>>
                    Törölve
                </option>

            </select>

            <input
                type="date"
                name="deadline"
                class="form-control mb-3"
                value="<?= htmlspecialchars($project["deadline"] ?? "") ?>">
            <label class="form-label">Ár</label>

            <input
                type="number"
                step="0.01"
                name="price"
                class="form-control mb-3"
                value="<?= htmlspecialchars($project["price"] ?? "") ?>">

            <label class="form-label">Verzió</label>
            <input
                type="text"
                name="version_number"
                class="form-control mb-3"
                value="<?= htmlspecialchars($latestVersion["version_number"] ?? "") ?>">

            <label class="form-label">Github URL</label>
            <input
                type="url"
                name="github_url"
                class="form-control mb-3"
                value="<?= htmlspecialchars($project["github_url"] ?? "") ?>">

            <label class="form-label">Élő oldal URL</label>
            <input
                type="url"
                name="live_url"
                class="form-control mb-3"
                value="<?= htmlspecialchars($project["live_url"] ?? "") ?>">

            <button class="btn btn-primary" type="submit">
                Módosítás mentése
            </button>

            <a href="index.php" class="btn btn-secondary">
                Vissza
            </a>
        </form>
    </div>

</body>

</html>