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

        <div class="content">
            <h1>Bienvenido al Dashboard</h1>
            <p>Esta es la página principal del panel de control. Aquí puedes acceder a las diferentes secciones del sitio.</p>
            <section>
                <h2>Resumen de actividades</h2>
                <p>Aquí puedes ver un resumen de tus actividades recientes.</p>
            </section>
        </div>
    </main>

    <footer>
    <!--    <?= $footer; ?> Renderiza el pie de página, si lo tienes -->
    </footer>
</body>

</html>
