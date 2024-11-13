<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?= $head ?>
    <style>
        /* Estilos básicos */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .admin-dashboard {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            font-size: 36px;
            color: #333;
            margin-bottom: 40px;
            font-weight: bold;
        }

        .admin-options {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .option-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .option-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .option-card h2 {
            font-size: 28px;
            color: #007bff;
            margin-bottom: 20px;
        }

        .option-card p {
            font-size: 16px;
            color: #777;
            margin-bottom: 25px;
        }

        .option-card .btn {
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 6px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .option-card .btn:hover {
            background-color: #0056b3;
        }

        .footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
        
        /* Estilos adicionales para la lista de usuarios */
        .usuarios-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .usuario-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .usuario-card h3 {
            font-size: 20px;
            color: #007bff;
        }

        .usuario-card p {
            font-size: 14px;
            color: #555;
            margin: 10px 0;
        }

        .usuario-card .btn {
            padding: 8px 16px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .usuario-card .btn:hover {
            background-color: #218838;
        }

        .usuario-card .btn-danger {
            background-color: #dc3545;
        }

        .usuario-card .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <?= $header ?>

    <div class="admin-dashboard">
        <h1>Gestión de Usuarios</h1>

        <div class="admin-options">
            <!-- Lista de Usuarios -->
            <div class="option-card">
                <i class="fas fa-users"></i>
                <h2>Usuarios Registrados</h2>
                <p>Consulta y gestiona los usuarios registrados en el sistema.</p>
                <a href="admin/usuarios/crear" class="btn">Agregar Usuario</a>
            </div>
        </div>

        <h2>Lista de Usuarios</h2>

        <!-- Mostrar los usuarios -->
        <div class="usuarios-list">
            <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $usuario): ?>
                    <div class="usuario-card">
                        <h3><?= htmlspecialchars($usuario->nombre) ?></h3>
                        <p>Email: <?= htmlspecialchars($usuario->email) ?></p>
                        <p>Rol: <?= htmlspecialchars($usuario->rol) ?></p>
                        <a href="admin/usuarios/ver/<?= $usuario->id ?>" class="btn">Ver Detalles</a>
                        <a href="admin/usuarios/eliminar/<?= $usuario->id ?>" class="btn btn-danger">Eliminar</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay usuarios registrados.</p>
            <?php endif; ?>
        </div>

    </div>

    <?= $footer ?>
</body>
</html>
