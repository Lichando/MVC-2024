<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?= $head ?> <!-- Aquí incluirías los metadatos, CSS, JS, etc. -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
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
            grid-template-columns: 1fr;
            gap: 20px;
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
    </style>
</head>
<body>
    <?= $header ?> <!-- Aquí incluirías el encabezado común de tu sitio -->

    <div class="admin-dashboard">
        <h1>Gestionar Inmobiliarias</h1>

        <div class="admin-options">
            <!-- Lista de Inmobiliarias -->
            <div class="option-card">
                <i class="fas fa-building"></i>
                <h2>Inmobiliarias Registradas</h2>
                <p>Gestiona las inmobiliarias registradas en el sistema.</p>
                <a href="admin/inmobiliarias/crear" class="btn">Agregar Inmobiliaria</a>
                <a href="admin/inmobiliarias/listar" class="btn">Ver Inmobiliarias</a>
            </div>
        </div>
    </div>

    <?= $footer ?> <!-- Pie de página común del sitio -->
</body>
</html>
