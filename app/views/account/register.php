<?php $isLogin = false; ?>
<!DOCTYPE html>
<head>
    <?=$head?>
</head>
<body>
    <header>
        <?=$header?>
    </header>

    <div class="form-container">
        <h2><?= isset($isLogin) && $isLogin ? 'Iniciar sesión' : 'Registrar cuenta' ?></h2>

        <!-- Mostrar error si existe -->
        <?php if (isset($error)): ?>
            <div class="error">
                <p><?= htmlspecialchars($error) ?></p>
            </div>
        <?php endif; ?>

        <form action="" method="post">
            <div>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" <?= isset($isLogin) && $isLogin ? 'style="display:none"' : '' ?> required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div>
                <button type="submit"><?= isset($isLogin) && $isLogin ? 'Iniciar sesión' : 'Registrar' ?></button>
            </div>
        </form>

        <p><?= isset($isLogin) && $isLogin ? '¿No tienes cuenta? <a href="register">Regístrate aquí</a>' : '¿Ya tienes una cuenta? <a href="login">Inicia sesión aquí</a>' ?></p>
    </div>

    <footer>
        <?= $footer; ?>
    </footer>
    <?= $scripts ?>
</body>
</html>
