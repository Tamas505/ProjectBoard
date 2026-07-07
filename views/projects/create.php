<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>Új projekt - ProjectBoard</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Saját stíluslap -->
    <link
        rel="stylesheet"
        href="/ProjectBoard/public/css/style.css?v=1.0">
</head>

<body class="bg-light">

<div class="container py-4">

    <!-- Oldal címe -->
    <h1 class="mb-4">
        Új projekt hozzáadása
    </h1>

    <!-- Hibaüzenetek -->
    <?php if (!empty($errors)): ?>

        <div class="alert alert-danger">

            <?php foreach ($errors as $error): ?>
                <div><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>

        </div>

    <?php endif; ?>

    <!-- Projekt létrehozó űrlap -->
    <form method="POST">

        <!-- Projekt címe -->
        <div class="mb-3">

            <label class="form-label">
                Projekt neve
            </label>

            <input
                type="text"
                name="title"
                class="form-control"
                placeholder="Projekt neve"
                autofocus
                required>

        </div>

        <!-- Leírás -->
        <div class="mb-3">

            <label class="form-label">
                Leírás
            </label>

            <textarea
                name="description"
                class="form-control"
                rows="3"
                placeholder="Leírás"></textarea>

        </div>

        <!-- Projekt státusza -->
        <div class="mb-3">

            <label class="form-label">
                Státusz
            </label>

            <select name="status" class="form-select">

                <option value="planning">Tervezés</option>
                <option value="active">Aktív</option>
                <option value="completed">Kész</option>
                <option value="cancelled">Törölve</option>

            </select>

        </div>

        <!-- Projekt típusa -->
        <div class="mb-3">

            <label class="form-label">
                Projekt típusa
            </label>

            <select name="type" class="form-select">

                <option value="personal">Saját projekt</option>
                <option value="client">Ügyfélprojekt</option>

            </select>

        </div>

        <!-- Határidő -->
        <div class="mb-3">

            <label class="form-label">
                Fejlesztési határidő
            </label>

            <input
                type="date"
                name="deadline"
                class="form-control">

        </div>

        <!-- Megjegyzések -->
        <div class="mb-3">

            <label class="form-label">
                Megjegyzések
            </label>

            <textarea
                name="notes"
                class="form-control"
                rows="4"
                placeholder="Megjegyzések"></textarea>

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
                placeholder="Projekt ára">

        </div>

        <!-- GitHub -->
        <div class="mb-3">

            <label class="form-label">
                GitHub URL
            </label>

            <input
                type="url"
                name="github_url"
                class="form-control"
                placeholder="https://github.com/...">

        </div>

        <!-- Élő oldal -->
        <div class="mb-4">

            <label class="form-label">
                Élő oldal URL
            </label>

            <input
                type="url"
                name="live_url"
                class="form-control"
                placeholder="https://...">

        </div>

        <!-- Műveleti gombok -->
        <button
            class="btn btn-primary"
            type="submit">
            Projekt mentése
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