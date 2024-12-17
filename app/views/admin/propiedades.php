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
            color: #4caf50;
            margin-bottom: 20px;
        }

        .option-card p {
            font-size: 16px;
            color: #777;
            margin-bottom: 25px;
        }

        .option-card .btn {
            padding: 12px 25px;
            background-color: #4caf50;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 6px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .option-card .btn:hover {
            background-color: #1d4e1f;
        }

        .footer {
            background-color: #4caf50;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 40px;
        }
        
        /* Estilos adicionales para la lista de propiedades */
        .propiedad-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .propiedad-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .propiedad-card h3 {
            font-size: 20px;
            color: #4caf50;
        }

        .propiedad-card p {
            font-size: 14px;
            color: #555;
            margin: 10px 0;
        }

        .propiedad-card .btn {
            padding: 8px 16px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 6px;
            transition: background-color 0.3s ease;
        }

        .propiedad-card .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <?= $header ?>

    <div class="admin-dashboard">
        <h1>Gestión de Propiedades</h1>

        <div class="admin-options">
            <!-- Lista de Propiedades -->
            <div class="option-card">
                <i class="fas fa-home"></i>
                <h2>Propiedades Registradas</h2>
                <p>Consulta y gestiona las propiedades registradas en el sistema.</p>
                <a href="admin/propiedades/crear" class="btn">Agregar Propiedad</a>
            </div>
        </div>

        <h2>Lista de Propiedades</h2>

        <!-- Mostrar las propiedades -->
        <div class="propiedad-list">
            <?php if (!empty($propiedades)): ?>
                <?php foreach ($propiedades as $propiedad): ?>
                    <div class="propiedad-card">
                        <h3><?= htmlspecialchars($propiedad['nombre']) ?></h3>
                        <p>Ubicación: <?= htmlspecialchars($propiedad['ubicacion']) ?></p>
                        <p>Precio: $<?= number_format($propiedad['precio'], 2) ?></p>
                        <a href="admin/propiedades/ver/<?= $propiedad['id'] ?>" class="btn">Ver Detalles</a>
                        <a href="admin/propiedades/eliminar/<?= $propiedad['id'] ?>" class="btn" style="background-color: #dc3545;">Eliminar</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay propiedades registradas.</p>
            <?php endif; ?>
        </div>

    </div>

    <?= $footer ?>
</body>
</html>
