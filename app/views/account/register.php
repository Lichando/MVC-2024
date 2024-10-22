<!DOCTYPE html>
<head>
<?=$head?>
<body>

<header>
    <?=$header?>
</header>
    
<div class="register-container">
            <h2>Registrar Cuenta</h2>

            <?php if (isset($error)): ?>
                <div class="error">
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <form action="/account/register" method="post">
                <div>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div>
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div>
                    <button type="submit">Registrar</button>
                </div>
            </form>

            <p>Ya tienes una cuenta? <a href="/account/login">Inicia sesión aquí</a></p>
        </div>
        
</body>
</html>