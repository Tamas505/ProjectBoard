<?php

/** @var array<string, mixed> $project */
/** @var array $versions */
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($project["title"]) ?> - ProjectBoard</title>

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

        <!-- Visszalépés -->
        <a href="index.php" class="btn btn-secondary mb-3">
            Vissza a projektekhez
        </a>

        <!-- Projekt adatai -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">

                <h1 class="h2 mb-3">
                    <?= htmlspecialchars($project["title"]) ?>
                </h1>

                <p class="text-muted">
                    <?= htmlspecialchars($project["description"]) ?>
                </p>

                <hr>

                <p>
                    <strong>Típus:</strong>
                    <?= $project["type"] === "personal" ? "Saját projekt" : "Ügyfélprojekt" ?>
                </p>

                <?php
                $statusText = match ($project["status"]) {
                    "planning" => "Tervezés",
                    "active" => "Aktív",
                    "completed" => "Kész",
                    "cancelled" => "Törölve",
                    default => $project["status"]
                };

                $statusClass = match ($project["status"]) {
                    "planning" => "bg-warning text-dark",
                    "active" => "bg-primary",
                    "completed" => "bg-success",
                    "cancelled" => "bg-danger",
                    default => "bg-secondary"
                };
                ?>

                <p>
                    <strong>Státusz:</strong>

                    <span class="badge <?= $statusClass ?>">
                        <?= htmlspecialchars($statusText) ?>
                    </span>
                </p>

                <?php if (!empty($project["deadline"])): ?>
                    <p>
                        <strong>Határidő:</strong>
                        <?= date("Y. m. d.", strtotime($project["deadline"])) ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($project["price"])): ?>
                    <p>
                        <strong>Ár:</strong>
                        <?= number_format((float) $project["price"], 0, ",", " ") ?> Ft
                    </p>
                <?php endif; ?>

                <?php if (!empty($project["notes"])): ?>
                    <div class="alert alert-warning">
                        <strong>Fejlesztői megjegyzések:</strong><br>
                        <?= nl2br(htmlspecialchars($project["notes"])) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($project["github_url"])): ?>
                    <p>
                        <strong>GitHub:</strong>

                        <a
                            href="<?= htmlspecialchars($project["github_url"]) ?>"
                            target="_blank"
                            rel="noopener noreferrer">
                            <?= htmlspecialchars($project["github_url"]) ?>
                        </a>
                    </p>
                <?php endif; ?>

                <?php if (!empty($project["live_url"])): ?>
                    <p>
                        <strong>Élő oldal:</strong>

                        <a
                            href="<?= htmlspecialchars($project["live_url"]) ?>"
                            target="_blank"
                            rel="noopener noreferrer">
                            <?= htmlspecialchars($project["live_url"]) ?>
                        </a>
                    </p>
                <?php endif; ?>

            </div>
        </div>

        <!-- Verziónapló fejléc -->
        <div class="version-header">
            <div>
                <h2 class="h4 mb-1">
                    Verziónapló
                </h2>

                <p class="text-muted mb-0">
                    A projekt fejlesztési története
                </p>
            </div>

            <a
                href="index.php?action=createVersion&project_id=<?= $project["id"] ?>"
                class="btn btn-primary">
                + Új verzió hozzáadása
            </a>
        </div>

        <!-- Üres verziónapló üzenet -->
        <?php if (empty($versions)): ?>
            <div class="alert alert-info">
                Ehhez a projekthez még nincs verzióbejegyzés.
            </div>
        <?php endif; ?>

        <!-- Verziólista -->
        <div class="version-list">

            <?php foreach ($versions as $version): ?>

                <?php
                $typeClass = match ($version["change_type"]) {
                    "feature" => "version-type-feature",
                    "bugfix" => "version-type-bugfix",
                    "design" => "version-type-design",
                    "content" => "version-type-content",
                    "release" => "version-type-release",
                    default => "version-type-default"
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

                <!-- Egy verzióbejegyzés -->
                <div class="version-row">

                    <div class="version-left">
                        <div class="version-number">
                            <?= htmlspecialchars($version["version_number"]) ?>
                        </div>

                        <div class="version-dot"></div>
                    </div>

                    <div class="version-content">

                        <div class="version-card">

                            <!-- Verzió típusa és élesítési állapota -->
                            <div class="version-card-top">

                                <span class="version-type <?= $typeClass ?>">
                                    <?= htmlspecialchars($typeText) ?>
                                </span>

                                <span class="version-deployed <?= $version["deployed"] ? "is-deployed" : "not-deployed" ?>">
                                    <?= $version["deployed"] ? "Élesítve" : "Nincs élesítve" ?>
                                </span>

                            </div>

                            <!-- Verzió dátuma -->
                            <p class="text-muted small mb-2">
                                📅
                                <?= date("Y. m. d. H:i", strtotime($version["created_at"])) ?>
                            </p>

                            <!-- Verzió leírása -->
                            <h5 class="mb-3">
                                <?= htmlspecialchars($version["description"]) ?>
                            </h5>

                            <!-- Verzió műveletek -->
                            <div class="version-actions mt-3">

                                <button
                                    class="btn btn-sm btn-outline-primary"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#versionEdit<?= $version["id"] ?>">
                                    ✏️ Szerkesztés
                                </button>

                                <form
                                    method="POST"
                                    action="index.php?action=deleteVersion"
                                    class="d-inline"
                                    onsubmit="return confirm('Biztosan törölni szeretnéd ezt a verziót?');">

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $version["id"] ?>">

                                    <button
                                        type="submit"
                                        class="btn btn-sm btn-outline-danger">
                                        🗑️ Törlés
                                    </button>

                                </form>

                            </div>

                            <!-- Verzió szerkesztő űrlap -->
                            <div
                                class="collapse mt-3"
                                id="versionEdit<?= $version["id"] ?>">

                                <form
                                    method="POST"
                                    action="index.php?action=updateVersion">

                                    <input
                                        type="hidden"
                                        name="id"
                                        value="<?= $version["id"] ?>">

                                    <div class="mb-3">
                                        <label class="form-label">
                                            Verziószám
                                        </label>

                                        <input
                                            type="text"
                                            name="version_number"
                                            class="form-control"
                                            value="<?= htmlspecialchars($version["version_number"]) ?>"
                                            required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            Változás típusa
                                        </label>

                                        <select
                                            name="change_type"
                                            class="form-select">

                                            <option value="feature" <?= $version["change_type"] === "feature" ? "selected" : "" ?>>
                                                Új funkció
                                            </option>

                                            <option value="bugfix" <?= $version["change_type"] === "bugfix" ? "selected" : "" ?>>
                                                Hibajavítás
                                            </option>

                                            <option value="design" <?= $version["change_type"] === "design" ? "selected" : "" ?>>
                                                Design
                                            </option>

                                            <option value="content" <?= $version["change_type"] === "content" ? "selected" : "" ?>>
                                                Tartalom
                                            </option>

                                            <option value="release" <?= $version["change_type"] === "release" ? "selected" : "" ?>>
                                                Kiadás
                                            </option>

                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            Leírás
                                        </label>

                                        <textarea
                                            name="description"
                                            class="form-control"
                                            rows="3"
                                            required><?= htmlspecialchars($version["description"]) ?></textarea>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="deployed"
                                            id="deployed_<?= $version["id"] ?>"
                                            <?= $version["deployed"] ? "checked" : "" ?>>

                                        <label
                                            class="form-check-label"
                                            for="deployed_<?= $version["id"] ?>">
                                            Élesítve
                                        </label>
                                    </div>

                                    <button
                                        type="submit"
                                        class="btn btn-primary btn-sm">
                                        Mentés
                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

    <!-- Bootstrap JS a collapse működéséhez -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>