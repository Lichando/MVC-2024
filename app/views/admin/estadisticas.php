<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?= $head ?>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
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

        .btn {
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            font-weight: bold;
            border-radius: 6px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .ranking-section {
            margin-bottom: 50px;
        }

        .ranking-section h2 {
            font-size: 24px;
            color: #007bff;
            margin-bottom: 15px;
        }

        .ranking-section table {
            width: 100%;
            border: 1px solid #ddd;
        }

        .ranking-section table td, .ranking-section table th {
            padding: 10px;
            text-align: left;
        }

        .ranking-section table th {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <?= $header ?>

    <div class="admin-dashboard">
        <h1>Estadísticas Generales</h1>

        <div class="ranking-section">
            <h2>Top Inmobiliarias por Propiedades Vendidas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Inmobiliaria</th>
                        <th>Propiedades Vendidas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topInmobiliarias as $inmobiliaria): ?>
                        <tr>
                            <td><?= htmlspecialchars($inmobiliaria['inmobiliaria_nombre']) ?></td>
                            <td><?= htmlspecialchars($inmobiliaria['propiedades_vendidas']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="ranking-section">
            <h2>Top Propiedades Más Vistas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Propiedad</th>
                        <th>Total de Vistas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topPropiedades as $propiedad): ?>
                        <tr>
                            <td><?= htmlspecialchars($propiedad['propiedad_nombre']) ?></td>
                            <td><?= htmlspecialchars($propiedad['total_vistas']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="ranking-section">
            <h2>Top Vendedores Más Consultados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Vendedor</th>
                        <th>Total de Consultas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topVendedores as $vendedor): ?>
                        <tr>
                            <td><?= htmlspecialchars($vendedor['agente_nombre']) ?></td>
                            <td><?= htmlspecialchars($vendedor['total_consultas']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="ranking-section">
            <h2>Top Inmobiliarias por Puntuación</h2>
            <table>
                <thead>
                    <tr>
                        <th>Inmobiliaria</th>
                        <th>Puntuación Promedio</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topInmobiliariasPorPuntuacion as $inmobiliaria): ?>
                        <tr>
                            <td><?= htmlspecialchars($inmobiliaria['inmobiliaria_nombre']) ?></td>
                            <td><?= number_format($inmobiliaria['puntuacion_promedio'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="admin-options">
            <div class="option-card">
                <i class="fas fa-chart-line"></i>
                <h2>Estadísticas</h2>
                <p>Consulta las estadísticas detalladas de propiedades, agentes, y más.</p>
                <a href="admin/estadisticas" class="btn">Ver Estadísticas</a>
            </div>
        </div>
    </div>

    <?= $footer ?>
</body>
</html>
