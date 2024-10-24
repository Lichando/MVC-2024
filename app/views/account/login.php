<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>
</head>

<body>
    <header>
        <?= $header; ?> <!-- Renderiza el encabezado, si lo tienes -->
    </header>

    <main>
        <div class="login-container">
            <h2>Iniciar Sesión</h2>

            <?php if (isset($error)): ?>
                <div class="error">
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <form action="" method="post">
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" >
                </div>
                <div>
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" >
                </div>
                <div>
                    <button type="submit">Iniciar Sesión</button>
                </div>
            </form>

            <p>No tienes una cuenta? <a href="register">Regístrate aquí</a></p>
        </div>

    </main>

    <footer>
        <?= $footer; ?> <!-- Renderiza el pie de página, si lo tienes -->
    </footer>
</body>

</html>