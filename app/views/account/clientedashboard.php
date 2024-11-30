<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>
    <style>
        /* Contenedor principal del dashboard */
        .dashboard-container {
            display: flex;
            margin: 20px;
        }

        /* Barra lateral */
        .dashboard-sidebar {
            width: 250px;
            background-color: #f1f1f1;
            padding: 20px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .dashboard-sidebar h3 {
            font-size: 1.5em;
            margin-bottom: 15px;
        }

        .dashboard-sidebar ul {
            list-style: none;
            padding-left: 0;
        }

        .dashboard-sidebar ul li {
            margin-bottom: 10px;
        }

        .dashboard-sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-size: 1.1em;
            transition: color 0.3s;
        }

        .dashboard-sidebar ul li a:hover {
            color: #007bff;
        }

        /* Sección principal */
        .dashboard-main {
            flex-grow: 1;
            margin-left: 20px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Bienvenida al usuario */
        .welcome-message {
            font-size: 1.3em;
            margin-bottom: 20px;
        }

        .welcome-message strong {
            color: #007bff;
        }

        /* Estilo de los botones de acción */
        .action-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 15px 30px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 1.1em;
            text-align: center;
            transition: background-color 0.3s;
        }

        .action-btn:hover {
            background-color: #0056b3;
        }

        /* Contenedor de las opciones */
        .dashboard-options {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 30px;
        }

        .dashboard-option {
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .dashboard-option:hover {
            transform: translateY(-5px);
        }

        .dashboard-option h3 {
            margin-bottom: 15px;
            font-size: 1.3em;
        }

        /* Nueva sección para convertirse en inmobiliaria */
        .convert-inmobiliaria {
            background-color: #fff3cd;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
            text-align: center;
        }

        .convert-inmobiliaria h3 {
            font-size: 1.5em;
            margin-bottom: 15px;
        }

        .convert-inmobiliaria .action-btn {
            background-color: #ffc107;
        }

        .convert-inmobiliaria .action-btn:hover {
            background-color: #e0a800;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <header>
        <?= $header; ?>
    </header>
        <main>
            <div class="dashboard-container">
                <!-- Navegación lateral -->
                <aside class="dashboard-sidebar">
                    <h3>Opciones</h3>
                    <ul>
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
            <?= $footerDash; ?>
        </footer>
    </body>


    <footer>
        <?= $footerDash; ?>
    </footer>
</body>

</html>