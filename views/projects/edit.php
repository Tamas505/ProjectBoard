<?php

/** @var array<string, mixed> $project */
/** @var array<string, mixed>|false $latestVersion */
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>Projekt módosítása - ProjectBoard</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Saját stíluslap -->
    <link
        rel="stylesheet"
        href="/ProjectBoard/public/css/style.css?v=1.0">
</head>

<body class="bg-light text-dark">

    <div class="container py-4">

        <!-- Oldal címe -->
        <h1 class="mb-4">
            Projekt módosítása
        </h1>

        <!-- Hibaüzenetek -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Projekt módosító űrlap -->
        <form method="POST">

            <!-- Projekt neve -->
            <div class="mb-3">
                <label class="form-label">
                    Projekt neve
                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    value="<?= htmlspecialchars($project["title"]) ?>"
                    required>
            </div>

            <!-- Projekt leírása -->
            <div class="mb-3">
                <label class="form-label">
                    Projekt leírása
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="3"><?= htmlspecialchars($project["description"]) ?></textarea>
            </div>

            <!-- Fejlesztői megjegyzések -->
            <div class="mb-3">
                <label class="form-label">
                    Fejlesztői megjegyzések
                </label>

                <textarea
                    name="notes"
                    class="form-control"
                    rows="4"><?= htmlspecialchars($project["notes"] ?? "") ?></textarea>
            </div>

            <!-- Projekt típusa -->
            <div class="mb-3">
                <label class="form-label">
                    Projekt típusa
                </label>

                <select name="type" class="form-select">
                    <option value="personal" <?= $project["type"] === "personal" ? "selected" : "" ?>>
                        Saját projekt
                    </option>

                    <option value="client" <?= $project["type"] === "client" ? "selected" : "" ?>>
                        Ügyfélprojekt
                    </option>
                </select>
            </div>

            <!-- Projekt státusza -->
            <div class="mb-3">
                <label class="form-label">
                    Státusz
                </label>

                <select name="status" class="form-select">
                    <option value="planning" <?= $project["status"] === "planning" ? "selected" : "" ?>>
                        Tervezés
                    </option>

                    <option value="active" <?= $project["status"] === "active" ? "selected" : "" ?>>
                        Aktív
                    </option>

                    <option value="completed" <?= $project["status"] === "completed" ? "selected" : "" ?>>
                        Kész
                    </option>

                    <option value="cancelled" <?= $project["status"] === "cancelled" ? "selected" : "" ?>>
                        Törölve
                    </option>
                </select>
            </div>

            <!-- Fejlesztési határidő -->
            <div class="mb-3">
                <label class="form-label">
                    Fejlesztési határidő
                </label>

                <input
                    type="date"
                    name="deadline"
                    class="form-control"
                    value="<?= htmlspecialchars($project["deadline"] ?? "") ?>">
            </div>

            <!-- Ár -->
            <div class="mb-3">
                <label class="form-label">
                    Projekt ára (Ft)
                </label>

                <input
                    type="number"
                    step="0.01"
                    name="price"
                    class="form-control"
                    value="<?= htmlspecialchars($project["price"] ?? "") ?>">
            </div>

            <!-- Verziószám -->
            <div class="mb-3">
                <label class="form-label">
                    Verziószám
                </label>

                <input
                    type="text"
                    name="version_number"
                    class="form-control"
                    value="<?= htmlspecialchars($latestVersion["version_number"] ?? "") ?>">
            </div>

            <!-- GitHub URL -->
            <div class="mb-3">
                <label class="form-label">
                    GitHub URL
                </label>

                <input
                    type="url"
                    name="github_url"
                    class="form-control"
                    placeholder="https://github.com/..."
                    value="<?= htmlspecialchars($project["github_url"] ?? "") ?>">
            </div>

            <!-- Élő oldal URL -->
            <div class="mb-4">
                <label class="form-label">
                    Élő oldal URL
                </label>

                <input
                    type="url"
                    name="live_url"
                    class="form-control"
                    placeholder="https://..."
                    value="<?= htmlspecialchars($project["live_url"] ?? "") ?>">
            </div>

            <!-- Műveleti gombok -->
            <button
                class="btn btn-primary"
                type="submit">
                Módosítás mentése
            </button>

            <a
                href="index.php"
                class="btn btn-secondary">
                Vissza
            </a>

        </form>

    </div>

</body>

</html>