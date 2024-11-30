<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>
    <style>
        /* Estilo global para la página */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        header, footer {
            background-color: #343a40;
            color: white;
            padding: 20px;
            text-align: center;
        }

        /* Contenedor principal */
        .dashboard-container {
            display: flex;
            min-height: 100vh;
            flex-direction: row;
            background-color: #f1f1f1;
        }

        /* Barra lateral */
        .dashboard-sidebar {
            width: 250px;
            background-color: #007bff;
            color: white;
            padding: 20px;
            height: 100vh;
        }

        .dashboard-sidebar h3 {
            margin-top: 0;
        }

        .dashboard-sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .dashboard-sidebar ul li {
            margin-bottom: 15px;
        }

        .dashboard-sidebar ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .dashboard-sidebar ul li a:hover {
            text-decoration: underline;
        }

        /* Contenido principal */
        .dashboard-main {
            flex-grow: 1;
            margin-left: 20px;
            padding: 30px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .property-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .property-table th, .property-table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .property-table th {
            background-color: #007bff;
            color: white;
        }

        .property-table td {
            background-color: #f9f9f9;
        }

        .property-table td a {
            color: #007bff;
            text-decoration: none;
        }

        .property-table td a:hover {
            text-decoration: underline;
        }

        .no-properties-message {
            font-size: 1.2em;
            color: #d9534f;
            text-align: center;
            margin-top: 30px;
        }

        .form-container a {
            color: #007bff;
            text-decoration: none;
        }

        .form-container a:hover {
            text-decoration: underline;
        }

        /* Estilo para los botones */
        .btn {
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .btn:hover {
            background-color: #218838;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <header>
        <?= $header; ?>
    </header>

    <main>
        <div class="dashboard-container">
            <!-- Barra lateral -->
            <aside class="dashboard-sidebar">
                <h3>Opciones</h3>
                <ul>
                    <li><a href="../inmobiliaria/propiedades">Ver Propiedades</a></li>
                    <li><a href="../inmobiliaria/cargar">Cargar Propiedad</a></li>
                    <li><a href="logout">Cerrar Sesión</a></li>
                </ul>
            </aside>

            <!-- Contenido principal del dashboard -->
            <section class="dashboard-main">
                <h2>Lista de Propiedades</h2>

                <?php if (empty($propiedades)): ?>
                    <p class="no-properties-message"><?= $mensajeCargarPropiedad; ?></p>
                <?php else: ?>
                    <table class="property-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($propiedades as $propiedad): ?>
                                <tr>
                                    <td><?= $propiedad['id']; ?></td>
                                    <td><?= $propiedad['nombre']; ?></td>
                                    <td><?= $propiedad['descripcion']; ?></td>
                                    <td><?= $propiedad['precio']; ?> USD</td>
                                    <td>
                                        <a href="/inmobiliarias/editar/<?= $propiedad['id']; ?>" class="btn">Editar</a> |
                                        <a href="/inmobiliarias/eliminar/<?= $propiedad['id']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta propiedad?')">Eliminar</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>

                <p><a href="../inmobiliaria/cargar" class="btn">Cargar una nueva propiedad</a></p>
            </section>
        </div>
    </main>

    <footer>
        <?= $footerDash; ?>
    </footer>
</body>

</html>
