<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>Bejelentkezés - ProjectBoard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link
        rel="stylesheet"
        href="/ProjectBoard/public/css/style.css?v=11">
</head>

<body class="login-page">

    <div class="login-wrapper">

        <div class="login-card shadow">

            <h1 class="mb-2 text-center">ProjectBoard</h1>

            <h5 class="text-center mb-3">Adminisztrátori bejelentkezés</h5>

            <p class="text-center text-muted mb-4">Projektkezelő rendszer</p>



            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $error): ?>
                        <div><?= htmlspecialchars($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">
                        Felhasználónév
                    </label>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        placeholder="Felhasználónév">
                </div>

                <div class="mb-4">
                    <label class="form-label">
                        Jelszó
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Jelszó">
                </div>

                <div class="d-grid">
                    <button
                        type="submit"
                        class="btn btn-primary btn-lg">
                        Belépés
                    </button>
                </div>

            </form>

        </div>

    </div>

</body>

</html>