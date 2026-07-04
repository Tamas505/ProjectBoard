<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Bejelentkezés - ProjectBoard</title>

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >
</head>
<body class="bg-dark text-light">

<div class="container py-5" style="max-width: 500px;">
    <h1 class="mb-4">ProjectBoard - bejelentkezés</h1>

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
            name="username" 
            class="form-control mb-3" 
            placeholder="Felhasználónév"
        >

        <input 
            type="password" 
            name="password" 
            class="form-control mb-3" 
            placeholder="Jelszó"
        >

        <button type="submit" class="btn btn-primary">
            Belépés
        </button>
    </form>
</div>

</body>
</html>