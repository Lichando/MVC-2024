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

        /* Estilo de las propiedades */
        .property-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .property-item {
            width: calc(33.33% - 20px);
            background-color: #f8f8f8;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }

        .property-item:hover {
            transform: translateY(-5px);
        }

        .property-item img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .property-item h3 {
            font-size: 1.5em;
            margin-top: 10px;
        }

        .property-item p {
            font-size: 1em;
            margin: 5px 0;
        }

        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
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
                    <li><a href="cargar-propiedad">Cargar Propiedad</a></li>
                    <li><a href="consultas">Consultas</a></li>
                    <li><a href="logout">Cerrar Sesión</a></li>
                </ul>
            </aside>

            <!-- Contenido principal del dashboard -->
            <section class="dashboard-main">
                <!-- Dentro de dashboard.php -->
                <div class="welcome-message">
                    <p>Bienvenido, <strong><?= htmlspecialchars($userName); ?></strong></p>
                </div>


                <h2>Propiedades Cargadas</h2>

                <?php if (!empty($properties)): ?>
                    <div class="property-list">
                        <?php foreach ($properties as $property): ?>
                            <div class="property-item">
                                <img src="images/<?= $property['imagen']; ?>" alt="<?= $property['titulo']; ?>" class="property-image">
                                <h3><?= htmlspecialchars($property['titulo']); ?></h3>
                                <p><strong>Ubicación:</strong> <?= htmlspecialchars($property['ubicacion']); ?></p>
                                <p><strong>Precio:</strong> <?= htmlspecialchars($property['precio']); ?></p>
                                <p><strong>Descripción:</strong> <?= htmlspecialchars($property['descripcion']); ?></p>
                                <a href="detalle.php?id=<?= urlencode($property['titulo']); ?>" class="btn">Ver más</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No hay propiedades cargadas en este momento.</p>
                <?php endif; ?>
            </section>
        </div>
    </main>

    <footer>
        <?= $footerDash; ?>
    </footer>
</body>

</html>