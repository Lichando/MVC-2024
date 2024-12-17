<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>
</head>
<style>


</style>

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
                    <li><a href="../account/logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                </ul>
            </aside>

            <!-- Contenido principal del dashboard -->
            <section class="dashboard-main">
                <h2>Lista de Propiedades</h2>
                <p><a href="../inmobiliaria/cargar" class="btn">Cargar una nueva propiedad</a></p>

                <?php if (empty($propiedades)): ?>
                    <p class="no-properties-message"><?= $mensajeCargarPropiedad; ?></p>
                <?php else: ?>
                    <table class="property-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Precio</th>
                                <th>Accion</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($propiedades as $propiedad): ?>
                                <tr>
                                    <td><?= htmlspecialchars($propiedad->direccionFake); ?></td>
                                    <td>
                                        <?= htmlspecialchars(strlen($propiedad->descripcion) > 50 ? substr($propiedad->descripcion, 0, 50) . '...' : $propiedad->descripcion); ?>
                                    </td>

                                    <td><?= htmlspecialchars($propiedad->precio); ?> USD</td>
                                    <td>
                                        <button class="btn"
                                            onclick="abrirPopup('detalles', <?= htmlspecialchars(json_encode($propiedad)); ?>)">Ver
                                            Detalles</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        </div>

        <!-- Popup Overlay -->
        <div id="popup-overlay" class="popup-overlay" style="display: none;">
            <div id="popup-content" class="popup-content">
                <div id="popup-body"></div>
                <button class="popup-close" onclick="cerrarPopup()">Cerrar</button>
            </div>
        </div>
    </main>
    <footer>
        <?= $footer ?>
    </footer>

    <?= $scripts ?>
    <script>
        function abrirPopup(tipo, data) {
            const overlay = document.getElementById('popup-overlay');
            const body = document.getElementById('popup-body');
            body.innerHTML = '';

            if (tipo === 'detalles') {
                body.innerHTML = `
                <h2>Detalles de la propiedad</h2>
                <p><strong>Dirección Ficticia:</strong> ${data.direccionFake}</p>
                <p><strong>Dirección Real:</strong> ${data.direccionTrue}</p>
                <p><strong>Estado de la propiedad:</strong> ${data.id_estado}</p>
                <p><strong>Anuncio activo:</strong> ${data.activo}</p>
                <p><strong>Precio:</strong> ${data.precio} USD</p>
            `;
            }

            overlay.style.display = 'flex';
        }

        function cerrarPopup() {
            const overlay = document.getElementById('popup-overlay');
            overlay.style.display = 'none';
        }
    </script>

</body>

</html>