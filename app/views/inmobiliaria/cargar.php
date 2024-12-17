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
                    <h2>Cargar Nueva Propiedad</h2>
                    <?php if (isset($error) && !empty($error)): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" enctype="multipart/form-data">
                        <div>
                            
                            <label>Dirección ficticia de propiedad</label>
                            <input type="text" name="direccionFake" id="direccionFake">

                            <label>Dirección real</label>
                            <input type="text" name="direccionTrue" id="direccionTrue">
                            <div>
                            <label>Seleccione la operacion</label>
                            <select name="estado" id="estado">
                                <option value="1">Venta</option>
                                <option value="2">Alquiler</option>
                                <option value="3">Alquiler temporario</option>
                                <option value="4">Reservado</option>
                                <option value="5">Vendida</option>
                            </select>
                        </div>
                            <label>Valor de la propiedad</label>
                            <label for="moneda">Moneda:</label>
                            <select name="moneda" id="moneda">
                                <option value="USD">USD</option>
                                <option value="EUR">EUR</option>
                                <option value="ARS">ARS</option>
                                <!-- Otras opciones según tus necesidades -->
                            </select>
                            <input type="text" name="precio" id="precio">

                            <label>Descripción</label>
                            <textarea name="descripcion" id="descripcion"></textarea>

                            <label>Metros cuadrados</label>
                            <input type="text" name="metros" id="metros">
                        </div>
                        <div>
                            <label>Baños</label>
                            <select name="banos" id="banos">
                                <option value="1">1 baño</option>
                                <option value="2">2 baños</option>
                                <option value="3">3 baños</option>
                                <option value="4">4 baños</option>
                                <option value="5">5 baños</option>
                                <option value="6">6 o más baños</option>
                            </select>

                            <label>Ambientes</label>
                            <select name="ambientes" id="ambientes">
                                <option value="1">1 ambiente</option>
                                <option value="2">2 ambientes</option>
                                <option value="3">3 ambientes</option>
                                <option value="4">4 ambientes</option>
                                <option value="5">5 ambientes</option>
                                <option value="6">6 o más ambientes</option>
                            </select>

                            <label>Dormitorios</label>
                            <select name="dormitorios" id="dormitorios">
                                <option value="1">1 dormitorio</option>
                                <option value="2">2 dormitorios</option>
                                <option value="3">3 dormitorios</option>
                                <option value="4">4 dormitorios</option>
                                <option value="5">5 dormitorios</option>
                                <option value="6">6 o más dormitorios</option>
                            </select>
                        </div>
                        <div>
                            <input type="file" name="imagen1" id="imagen1">
                            <input type="file" name="imagen2" id="imagen2">
                            <input type="file" name="imagen3" id="imagen3">
                        </div>
                      

                        <!-- Para capturar el ID del usuario, pasamos esta variable desde el controlador -->
                        <input type="hidden" name="inmobiliaria_id" value="<?php echo $inmobiliariaId; ?>">
                        <input type="hidden" name="nombre_inmo" value="<?php echo $inmobiliariaNombre; ?>">

                        <button type="submit">Crear Propiedad</button>
                    </form>
                    <p><a href="../inmobiliaria/propiedades">Ver lista de propiedades</a></p>
                    
                </div>
            </section>
        </div>
    </main>

    <footer>
    <?= $footer ?>
    </footer>
    <?=$scripts?>
</body>

</html>