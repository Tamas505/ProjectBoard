<?php

/** @var array<string, mixed> $project */
/** @var array $errors */
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>Új verzió - ClientBoard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light text-dark">

    <div class="container py-4" style="max-width: 700px;">

        <a href="index.php?action=show&id=<?= $project["id"] ?>" class="btn btn-secondary mb-3">
            Vissza
        </a>

        <h1 class="mb-4">
            Új verzió: <?= htmlspecialchars($project["title"]) ?>
        </h1>

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
                name="version_number"
                class="form-control mb-3"
                placeholder="Verziószám, pl. 1.1.0">

            <select name="change_type" class="form-select mb-3">
                <option value="feature">Új funkció</option>
                <option value="bugfix">Hibajavítás</option>
                <option value="design">Design módosítás</option>
                <option value="content">Tartalmi módosítás</option>
                <option value="release">Kiadás</option>
            </select>

            <textarea
                name="description"
                class="form-control mb-3"
                placeholder="Mit módosítottál ebben a verzióban?"
                rows="5"></textarea>

            <textarea
                name="notes"
                class="form-control mb-3"
                placeholder="Fejlesztői megjegyzések"
                rows="4">
            </textarea>

            <div class="form-check mb-3">
                <input
                    class="form-check-input"
                    type="checkbox"
                    name="deployed"
                    id="deployed">

                <label class="form-check-label" for="deployed">
                    Élesítve / feltöltve
                </label>
            </div>

            <button type="submit" class="btn btn-primary">
                Verzió mentése
            </button>
        </form>

    </div>

</body>

</html>