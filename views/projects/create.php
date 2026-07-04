<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>Új projekt - ProjectBoard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-dark text-light">

    <div class="container py-4">
        <h1 class="mb-4">Új projekt hozzáadása</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?= htmlspecialchars($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input
                type="text"
                name="title"
                class="form-control mb-3"
                placeholder="Projekt neve">

            <textarea
                name="description"
                class="form-control mb-3"
                placeholder="Leírás"></textarea>

            <select name="status" class="form-select mb-3">
                <option value="planning">Tervezés</option>
                <option value="active">Aktív</option>
                <option value="completed">Kész</option>
                <option value="cancelled">Törölve</option>
            </select>

            <select name="type" class="form-select mb-3">
                <option value="personal">Saját projekt</option>
                <option value="client">Ügyfélprojekt</option>
            </select>

            <input
                type="date"
                name="deadline"
                class="form-control mb-3">

            <textarea
                name="notes"
                class="form-control mb-3"
                placeholder="Megjegyzések"></textarea>

            <input
                type="number"
                step="0.01"
                name="price"
                class="form-control mb-3"
                placeholder="Ár">

            <input
                type="url"
                name="github_url"
                class="form-control mb-3"
                placeholder="GitHub URL">

            <input
                type="url"
                name="live_url"
                class="form-control mb-3"
                placeholder="Élő oldal URL">

            <button class="btn btn-primary" type="submit">
                Mentés
            </button>

            <a href="index.php" class="btn btn-secondary">
                Vissza
            </a>
        </form>
    </div>

</body>

</html>