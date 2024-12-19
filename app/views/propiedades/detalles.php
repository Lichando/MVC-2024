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

                <div class="carouselpropiedad">
                    <div class="carouselpropiedad-track">
                        <!-- Verifica y carga las imágenes de la propiedad o la imagen predeterminada -->
                        <?php if (!empty($propiedad->img1)): ?>
                            <div class="carouselpropiedad-item">
                                <img src="../../<?= htmlspecialchars($propiedad->img1) ?>" alt="Imagen de la propiedad" />
                            </div>
                        <?php else: ?>
                            <div class="carouselpropiedad-item">
                                <img src="../../img/imgnull.png" alt="Imagen de la propiedad" />
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($propiedad->img2)): ?>
                            <div class="carouselpropiedad-item">
                                <img src="../../<?= htmlspecialchars($propiedad->img2) ?>" alt="Imagen de la propiedad" />
                            </div>
                        <?php else: ?>
                            <div class="carouselpropiedad-item">
                                <img src="../../img/imgnull.png" alt="Imagen de la propiedad" />
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($propiedad->img3)): ?>
                            <div class="carouselpropiedad-item">
                                <img src="../../<?= htmlspecialchars($propiedad->img3) ?>" alt="Imagen de la propiedad" />
                            </div>
                        <?php else: ?>
                            <div class="carouselpropiedad-item">
                                <img src="../../img/imgnull.png" alt="Imagen de la propiedad" />
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Controles de navegación, dentro del contenedor del carrusel -->
                    <button class="carouselpropiedad-control-prev" onclick="moveCarouselPropiedad('prev')">&#10094;</button>
                    <button class="carouselpropiedad-control-next" onclick="moveCarouselPropiedad('next')">&#10095;</button>
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

    <script>
        let currentIndex = 0;

        // Función para mover el carrusel de imágenes
        function moveCarouselPropiedad(direction) {
            const items = document.querySelectorAll('.carouselpropiedad-item');
            const totalItems = items.length;

            if (direction === 'next') {
                currentIndex = (currentIndex + 1) % totalItems; // Si llega al final, vuelve al principio
            } else {
                currentIndex = (currentIndex - 1 + totalItems) % totalItems; // Si llega al inicio, vuelve al final
            }

            // Calcula el desplazamiento necesario
            const offset = -currentIndex * 100;  // -100% para mover la imagen correctamente
            document.querySelector('.carouselpropiedad-track').style.transform = `translateX(${offset}%)`;
        }


    </script>
</body>

</html>