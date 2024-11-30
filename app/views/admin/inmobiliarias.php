<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?= $head ?> <!-- Aquí incluirías los metadatos, CSS, JS, etc. -->
    <style>
        /* Tu CSS personalizado */
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
    <?= $header ?> <!-- Aquí incluirías el encabezado común de tu sitio -->

    <div class="admin-dashboard">
        <h1>Gestionar Inmobiliarias</h1>

        <!-- Mensajes de éxito o error -->
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success">
                <?= $successMessage; ?>
            </div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger">
                <?= $error; ?>
            </div>
        <?php endif; ?>

        <!-- Formulario de Registro de Inmobiliaria -->
        <div class="register-container">
            <h2>Registrar Nueva Inmobiliaria</h2>
            <form method="POST">
                <label for="nombre">Nombre</label>
                <input type="text" name="nombre" id="nombre" required value="<?= isset($nombre) ? htmlspecialchars($nombre) : '' ?>">

                <label for="duenioInmobiliaria">Dueño</label>
                <input type="text" name="duenioInmobiliaria" id="duenioInmobiliaria" required value="<?= isset($duenioInmobiliaria) ? htmlspecialchars($duenioInmobiliaria) : '' ?>">

                <label for="matricula">Matrícula</label>
                <input type="text" name="matricula" id="matricula" required value="<?= isset($matricula) ? htmlspecialchars($matricula) : '' ?>">

                <label for="direccion">Dirección</label>
                <input type="text" name="direccion" id="direccion" required value="<?= isset($direccion) ? htmlspecialchars($direccion) : '' ?>">

                <label for="telefono">Teléfono</label>
                <input type="text" name="telefono" id="telefono" value="<?= isset($telefono) ? htmlspecialchars($telefono) : '' ?>">

                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">

                <button type="submit" class="btn">Registrar Inmobiliaria</button>
            </form>
        </div>

        <!-- Formulario de búsqueda -->
        <div class="search-container">
            <form method="GET" action="admin/inmobiliarias">
                <input type="text" name="buscar" id="buscar" placeholder="Buscar Inmobiliaria..."
                    value="<?= isset($buscar) ? htmlspecialchars($buscar) : '' ?>">
                <button type="submit" class="btn">Buscar</button>
            </form>
        </div>

        <!-- Lista de Inmobiliarias -->
        <div class="admin-options">
            <?php if (!empty($inmobiliarias)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inmobiliarias as $inmobiliaria): ?>
                            <tr>
                                <td><?= htmlspecialchars($inmobiliaria->nombre) ?></td>
                                <td><?= htmlspecialchars($inmobiliaria->email) ?></td>
                                <td><?= htmlspecialchars($inmobiliaria->telefono) ?></td>
                                <td><?= htmlspecialchars($inmobiliaria->direccion) ?></td>
                                <td><a href="admin/eliminarInmobiliaria/<?= $inmobiliaria->id ?>">Eliminar</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No se encontraron inmobiliarias.</p>
            <?php endif; ?>
        </div>
    </div>

    <?= $footer ?> <!-- Pie de página común del sitio -->

    <!-- Script para abrir y cerrar el modal -->
    <script>
        // Obtener el modal
        var modal = document.getElementById("inmobiliariaModal");

        // Obtener el botón que abre el modal
        var openModalBtn = document.getElementById("openModalBtn");

        // Obtener el botón de cierre del modal
        var closeModalBtn = document.getElementById("closeModalBtn");

        // Cuando el usuario hace clic en el botón, abrir el modal
        openModalBtn.onclick = function () {
            modal.style.display = "block";
        }

        // Cuando el usuario hace clic en el botón de cierre, cerrar el modal
        closeModalBtn.onclick = function () {
            modal.style.display = "none";
        }

        // Cuando el usuario haga clic fuera del modal, cerrarlo
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>

</html>
