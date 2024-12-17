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
                    <li><a href="../account/logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                </ul>
            </aside>


            <!-- Contenido principal del dashboard -->
            <section class="dashboard-main">
                <div class="form-container">
                    <h2>Editar Propiedad</h2>

                    <form method="POST" enctype="multipart/form-data">
                        <div>
                            <!-- El campo propiedad_id siempre debe tener el ID de la propiedad -->
                            <input type="hidden" name="propiedad_id" value="<?= htmlspecialchars($idpropiedad); ?>"
                                spellcheck="false" data-ms-editor="true">

                            <label>Dirección ficticia de propiedad</label>
                            <input type="text" name="direccionFake" id="direccionFake"
                                value="<?= isset($propiedad->direccionFake) ? htmlspecialchars($propiedad->direccionFake) : ''; ?>">

                            <label>Dirección real</label>
                            <input type="text" name="direccionTrue" id="direccionTrue"
                                value="<?= isset($propiedad->direccionTrue) ? htmlspecialchars($propiedad->direccionTrue) : ''; ?>">

                            <label>Valor de la propiedad</label>
                            <label for="moneda">Moneda:</label>
                            <select name="moneda" id="moneda">
                                <option value="USD" <?= isset($propiedad->moneda) && $propiedad->moneda == 'USD' ? 'selected' : ''; ?>>USD</option>
                                <option value="EUR" <?= isset($propiedad->moneda) && $propiedad->moneda == 'EUR' ? 'selected' : ''; ?>>EUR</option>
                                <option value="ARS" <?= isset($propiedad->moneda) && $propiedad->moneda == 'ARS' ? 'selected' : ''; ?>>ARS</option>
                            </select>
                            <input type="text" name="precio" id="precio"
                                value="<?= isset($propiedad->precio) ? htmlspecialchars($propiedad->precio) : ''; ?>">

                            <label>Descripción</label>
                            <textarea name="descripcion"
                                id="descripcion"><?= isset($propiedad->descripcion) ? htmlspecialchars($propiedad->descripcion) : ''; ?></textarea>

                            <label>Metros cuadrados</label>
                            <input type="text" name="metros" id="metros"
                                value="<?= isset($propiedad->metros) ? htmlspecialchars($propiedad->metros) : ''; ?>">
                        </div>

                        <div>
                            <label>Baños</label>
                            <select name="banos" id="banos">
                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                    <option value="<?= $i; ?>" <?= isset($propiedad->banos) && $propiedad->banos == $i ? 'selected' : ''; ?>>
                                        <?= $i; ?> baño<?= $i > 1 ? 's' : ''; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>

                            <label>Ambientes</label>
                            <select name="ambientes" id="ambientes">
                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                    <option value="<?= $i; ?>" <?= isset($propiedad->ambientes) && $propiedad->ambientes == $i ? 'selected' : ''; ?>>
                                        <?= $i; ?> ambiente<?= $i > 1 ? 's' : ''; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>

                            <label>Dormitorios</label>
                            <select name="dormitorios" id="dormitorios">
                                <?php for ($i = 1; $i <= 6; $i++): ?>
                                    <option value="<?= $i; ?>" <?= isset($propiedad->dormitorios) && $propiedad->dormitorios == $i ? 'selected' : ''; ?>>
                                        <?= $i; ?> dormitorio<?= $i > 1 ? 's' : ''; ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>

                        <div>
                            <label>Estado</label>
                            <select name="estado" id="estado">
                                <option value="1" <?= isset($propiedad->estado) && $propiedad->estado == 1 ? 'selected' : ''; ?>>Venta</option>
                                <option value="2" <?= isset($propiedad->estado) && $propiedad->estado == 2 ? 'selected' : ''; ?>>Alquiler</option>
                                <option value="3" <?= isset($propiedad->estado) && $propiedad->estado == 3 ? 'selected' : ''; ?>>Alquiler temporario</option>
                                <option value="4" <?= isset($propiedad->estado) && $propiedad->estado == 4 ? 'selected' : ''; ?>>Reservado</option>
                                <option value="5" <?= isset($propiedad->estado) && $propiedad->estado == 5 ? 'selected' : ''; ?>>Vendida</option>
                            </select>
                        </div>

                        <input type="hidden" name="inmobiliaria_id"
                            value="<?= isset($inmobiliariaId) ? $inmobiliariaId : ''; ?>">
                        <input type="hidden" name="nombre_inmo"
                            value="<?= isset($inmobiliariaNombre) ? $inmobiliariaNombre : ''; ?>">

                        <button type="submit">Guardar cambios de la propiedad</button>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <?= $footer; ?>
    </footer>
    <?= $scripts ?>
</body>

</html>