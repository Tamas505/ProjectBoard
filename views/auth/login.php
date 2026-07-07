<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>Bejelentkezés - ProjectBoard</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Saját stíluslap -->
    <link
        rel="stylesheet"
        href="/ProjectBoard/public/css/style.css?v=1.0">
</head>

<body class="login-page">

    <div class="login-wrapper">

        <div class="login-card shadow">

            <!-- Oldal címe -->
            <h1 class="mb-2 text-center">
                ProjectBoard
            </h1>

            <h5 class="text-center mb-3">
                Adminisztrátori bejelentkezés
            </h5>

            <p class="text-center text-muted mb-4">
                Projektkezelő rendszer
            </p>

            <!-- Hibaüzenetek megjelenítése -->
            <?php if (!empty($errors)): ?>

                <div class="alert alert-danger">

                    <?php foreach ($errors as $error): ?>

                        <div>
                            <?= htmlspecialchars($error) ?>
                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

            <!-- Bejelentkezési űrlap -->
            <form method="POST">

                <!-- Felhasználónév -->
                <div class="mb-3">

                    <label class="form-label">
                        Felhasználónév
                    </label>

                    <input
                        type="text"
                        name="username"
                        class="form-control"
                        placeholder="Felhasználónév"
                        autocomplete="username"
                        autofocus
                        required>

                </div>

                <!-- Jelszó -->
                <div class="mb-4">

                    <label class="form-label">
                        Jelszó
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Jelszó"
                        autocomplete="current-password"
                        required>

                </div>

                <!-- Bejelentkezés -->
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