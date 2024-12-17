<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <!-- Incluir Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <?= $head ?> <!-- Aquí incluirías los metadatos, CSS, JS, etc. -->
</head>
<body>
    <?= $header ?> 

    <div class="admin-dashboard">
        <h1>Bienvenido al Dashboard de Administrador</h1>

        <div class="admin-options">
            <!-- Opción 1: Propiedades -->
            <div class="option-card">
                <i class="fas fa-home"></i>
                <h2>Propiedades</h2>
                <p>Gestiona las propiedades registradas en el sistema.</p>
                <a href="../admin/propiedades" class="btn">Ver Propiedades</a>
            </div>

            <!-- Opción 2: Inmobiliarias -->
            <div class="option-card">
                <i class="fas fa-building"></i>
                <h2>Inmobiliarias</h2>
                <p>Gestiona las inmobiliarias asociadas a la plataforma.</p>
                <a href="../admin/inmobiliarias" class="btn">Ver Inmobiliarias</a>
            </div>

            <!-- Opción 3: Usuarios -->
            <div class="option-card">
                <i class="fas fa-users"></i>
                <h2>Usuarios Registrados</h2>
                <p>Consulta y gestiona los usuarios registrados en el sistema.</p>
                <a href="../admin/usuarios" class="btn">Ver Usuarios</a>
            </div>

            <!-- Opción 4: Estadísticas -->
            <div class="option-card">
                <i class="fas fa-chart-line"></i>
                <h2>Estadísticas</h2>
                <p>Visualiza las estadísticas de propiedades, usuarios y más.</p>
                <a href="../admin/estadisticas" class="btn">Ver Estadísticas</a>
            </div>
        </div>
    </div>

    <?= $footer ?> <!-- Pie de página común del sitio -->
</body>
</html>
