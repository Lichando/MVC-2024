<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>
    <style>
       

    </style>
</head>

<body>
    <header>
        <?= $header; ?>
    </header>

    <main>
    <div class="dashboard-container">
            <!-- Navegación lateral -->
            <aside class="dashboard-sidebar" id="menusegundo">
                <h3 id="menu-toggle">Opciones</h3>
                <ul id="menu-list">
                    <li><a href="dashboard">Ver Propiedades</a></li>
                    <li><a href="consultas">Consultas</a></li>
                    <li><a href="../account/logout">Cerrar Sesión</a></li>
                </ul>
            </aside>


        <div class="form-container">
            <div class="form-box">
                <h2>Inscripción</h2>
                <?php if (isset($error) && $error != ''): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['successMessage'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['successMessage']; ?>
                    </div>
                    <?php unset($_SESSION['successMessage']); ?>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data">
                    <label for="nombre">Nombre de la Inmobiliaria:</label>
                    <input type="text" name="nombre" id="nombre">

                    <label for="matricula">Matrícula:</label>
                    <input type="text" name="matricula" id="matricula">

                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" id="direccion">

                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" id="telefono">

                    <label for="email">Correo Electrónico:</label>
                    <input type="email" name="email" id="email">

                    <!-- Campo para subir una imagen -->
                    <label for="imagen">Imagen de la Inmobiliaria:</label>
                    <input type="file" name="imagen" id="imagen" accept="image/jpeg, image/png">

                    <button type="submit">Registrar Inmobiliaria</button>
                </form>

            </div>
        </div>
    </main>

    <footer>
        <?= $footer; ?>
    </footer>
    <?=$scripts;?>
</body>

</html>