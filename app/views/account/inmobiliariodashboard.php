<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>
</head>

<body>
    <header>
        <?= $header; ?>
    </header>

    <main>
            <div class="dashboard-container">
            <!-- Navegación lateral -->
            <aside class="dashboard-sidebar" id="menusegundo">
                <h3 id="menu-toggle">Opciones</h3>
                <ul id="menu-list">
                <li><a href="../inmobiliaria/propiedades" class="active"><i class="fas fa-list"></i> Ver
                            Propiedades</a></li>
                    <li><a href="../inmobiliaria/propiedadesActivas"><i class="fas fa-check-circle"></i> Ver Propiedades
                            activas</a></li>
                    <li><a href="../inmobiliaria/propiedadesInactivas"><i class="fas fa-times-circle"></i> Ver
                            Propiedades inactivas</a></li>
                    <li><a href="../inmobiliaria/cargar"><i class="fas fa-plus-circle"></i> Cargar Propiedad</a></li>
                    <li><a href="logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                </ul>
            </aside>



            <!-- Contenido principal del dashboard -->
            <section class="dashboard-main">
                <div class="welcome-message">
                    <?php if (isset($_SESSION['inmobiliaria_id'])): ?>
                        <p>Bienvenido, <strong><?= htmlspecialchars($userName); ?></strong> - Inmobiliaria:<strong><?= htmlspecialchars(string: $inmobiliariaNombre) ?></strong></p></p>
                       
                        <div class="welcome-message">
                </div>
                    <?php else: ?>
                        <p>No se ha encontrado la inmobiliaria.</p>
                    <?php endif; ?>
                </div>

                <!-- Estadísticas del Dashboard -->
                <section class="dashboard-stats">
                    <div class="stat-item">
                        <h4>Propiedades Activas</h4>
                        <p><?= $propiedadesActivasCount; ?></p>
                    </div>
                    <div class="stat-item">
                        <h4>Propiedades Inactivas</h4>
                        <p><?= $propiedadesInactivasCount; ?></p>
                    </div>
                    <div class="stat-item">
                        <h4>Total de Propiedades</h4>
                        <p><?= $totalPropiedadesCount; ?></p>
                    </div>
                </section>

                <!-- Notificaciones -->
                <div class="notifications">
                    <?php if (isset($mensaje)): ?>
                        <div class="alert <?= $mensaje['type']; ?>">
                            <?= htmlspecialchars($mensaje['message']); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </main>
    <footer>
        <?= $footer ?>
    </footer>

    <?= $scripts ?>
</body>

</html>