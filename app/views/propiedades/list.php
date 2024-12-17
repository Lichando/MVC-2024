<!DOCTYPE html>
<html lang="es">
<?= $head ?>

</head>

<body>
    <header>
        <?= $header ?>
    </header>
    <main>
    <h2 class="listadopr">Listado de propiedades </h2>
        <div class="catalogo-propiedades">
           
            <?php if (isset($propiedades) && count($propiedades) > 0): ?>
                <?php foreach ($propiedades as $propiedad): ?>
                    <div class="propiedad">
                        <h2><?= htmlspecialchars($propiedad->direccionFake ?? '') ?></h2>
                        <p><?= htmlspecialchars(strlen($propiedad->descripcion) > 50 ? substr($propiedad->descripcion, 0, 50) . '...' : $propiedad->descripcion); ?>
                        </p>
                        <p>Precio: <?= htmlspecialchars($propiedad->precio ?? '') ?> $</p>
                        <?php if (!empty($propiedad->imagen)): ?>
                            <img src="<?= htmlspecialchars($propiedad->img1 ?? '') ?>"
                                alt="<?= htmlspecialchars($propiedad->direccionFake ?? '') ?>">
                        <?php endif; ?>
                        <!-- Enlace para ver los detalles con la ID encriptada -->
                        <a href="detalles/<?= urlencode(base64_encode($propiedad->id)) ?>" class="ver-detalles">Ver detalles</a>



                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No hay propiedades disponibles.</p>
            <?php endif; ?>
        </div>
    </main>
    <footer>
        <?= $footer ?>
    </footer>
    <?= $scripts ?>
</body>

</html>