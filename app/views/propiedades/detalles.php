<!DOCTYPE html>
<html lang="es">
<?= $head ?> <!-- El contenido de la sección HEAD se inyecta aquí -->

</head>

<body>
    <header>
        <?= $header ?> <!-- El contenido del header se inyecta aquí -->
    </header>


    <main>
        <!-- Información principal de la propiedad -->
        <?php if (isset($propiedad)): ?>
            <div class="detalles-propiedad">

                <!-- Título de la propiedad -->
                <div class="propiedad-titulo">
                    <h1>
                        <?= htmlspecialchars($propiedad->direccionFake ?? 'No disponible') ?>
                        -
                        <?php
                        switch ($propiedad->id_estado) {
                            case 1:
                                echo "Venta";
                                break;
                            case 2:
                                echo "Alquiler";
                                break;
                            case 3:
                                echo "Alquiler temporario";
                                break;
                            default:
                                echo "No disponible"; // En caso de que no coincida con ningún valor
                                break;
                        }
                        ?>
                    </h1>
                </div>


                <!-- Precio y moneda -->
                <div class="propiedad-precio">
                    <p><strong>Precio:</strong> <?= htmlspecialchars($propiedad->precio ?? 'No disponible') ?>
                        <?= htmlspecialchars($propiedad->moneda ?? 'No disponible') ?>
                    </p>
                </div>

                <!-- Imágenes de la propiedad -->
                <div class="propiedad-imagenes">
                    <?php if (!empty($propiedad->img1)): ?>
                        <img src="../../<?= htmlspecialchars($propiedad->img1) ?>" alt="Imagen de la propiedad" />
                    <?php endif; ?>
                    <?php if (!empty($propiedad->img2)): ?>
                        <img src="../../<?= htmlspecialchars($propiedad->img2) ?>" alt="Imagen de la propiedad" />
                    <?php endif; ?>
                    <?php if (!empty($propiedad->img3)): ?>
                        <img src="../../<?= htmlspecialchars($propiedad->img3) ?>" alt="Imagen de la propiedad" />
                    <?php endif; ?>
                </div>

                <!-- Detalles adicionales -->
                <div class="propiedad-detalles">
                    <p><strong>Ubicación:</strong> <?= htmlspecialchars($propiedad->direccionTrue ?? 'No disponible') ?>
                    </p>
                    <p><strong>Descripción:</strong> <?= htmlspecialchars($propiedad->descripcion ?? 'No disponible') ?>
                    </p>

                    <p><strong>Contacto:</strong> <?= htmlspecialchars($inmobiliaria ?? 'No disponible') ?></p>
                </div>
            </div>
        <?php else: ?>
            <p>No se pudo obtener la propiedad.</p>
        <?php endif; ?>
    </main>


    <footer>
        <?= $footer ?>
    </footer>
    <?= $scripts ?>
</body>

</html>