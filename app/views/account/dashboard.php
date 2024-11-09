<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>

</head>

<body>
    <header>
        <?= $header; ?> <!-- Renderiza el encabezado, si lo tienes -->
    </header>

    <main>
        <nav>
            <h3>Navegación</h3>
            <ul>
                <li><a href="dashboard">Inicio</a></li>
                <li><a href="perfil">Perfil</a></li>
                <li><a href="configuracion">Configuración</a></li>
                <li><a href="logout">Cerrar sesión</a></li>
            </ul>
        </nav>
    </main>
    
    <footer>
    <!--    <?= $footerDash; ?> Renderiza el pie de página, si lo tienes -->
    </footer>
</body>

</html>
