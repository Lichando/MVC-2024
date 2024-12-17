<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>

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
                    <li><a href="logout">Cerrar Sesión</a></li>
                </ul>
            </aside>

            <!-- Contenido principal del dashboard -->
            <section class="dashboard-main">

                <!-- Mostrar mensaje de éxito -->
                <?php if (isset($error)): ?>
                    <div class="error"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>


                <div class="welcome-message">
                    <p>Bienvenido, <strong><?= htmlspecialchars($userName); ?></strong></p>
                </div>
                <!-- Sección para convertirse en inmobiliaria -->
                <div class="convert-inmobiliaria">
                    <h3>¿Quieres convertirte en Inmobiliaria?</h3>
                    <p>Si deseas gestionar propiedades y ofrecer tus servicios inmobiliarios, puedes comenzar el
                        proceso
                        aquí.</p>
                    <a href="../clientes/inscripcion" class="action-btn">Ser Inmobiliaria</a>
                </div>

                <!-- Opciones disponibles -->
                <div class="dashboard-options">
                    <div class="dashboard-option">
                        <h3>Conversaciones con Inmobiliarias</h3>
                        <a href="../clientes/conversaciones.php" class="action-btn">Ver Conversaciones</a>
                    </div>
                    <div class="dashboard-option">
                        <h3>Estado de Tasación</h3>
                        <a href="../clientes/tasaciones.php" class="action-btn">Ver Estado</a>
                    </div>
                    <div class="dashboard-option">
                        <h3>Propiedades que has Mirado</h3>
                        <a href="../clientes/propiedades_vistadas.php" class="action-btn">Ver Propiedades</a>
                    </div>


            </section>
        </div>
    </main>

    <footer>
        <?= $footer ?>
    </footer>
    <?=$scripts?>
</body>

</html>