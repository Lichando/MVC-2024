<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Propiedades</title>
    <link rel="stylesheet" href="path/to/your/styles.css"> <!-- Incluye tus estilos -->
</head>
<style>
    /* Estilo base para el cuerpo */
body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Estilo del encabezado */
h1 {
    text-align: center;
    margin-top: 20px;
    color: #007BFF;
}

/* Contenedor principal del catálogo */
.catalogo-propiedades {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    padding: 20px;
}

/* Estilo individual para cada propiedad */
.propiedad {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    margin: 15px;
    padding: 20px;
    width: 300px; /* Ancho fijo */
    transition: transform 0.2s; /* Efecto al pasar el mouse */
}

/* Efecto al pasar el mouse sobre la propiedad */
.propiedad:hover {
    transform: scale(1.05);
}

/* Estilo para el título de la propiedad */
.propiedad h2 {
    font-size: 1.5em;
    margin: 0;
    color: #333;
}

/* Estilo para la descripción y el precio */
.propiedad p {
    margin: 10px 0;
}

/* Estilo para las imágenes de la propiedad */
.propiedad img {
    max-width: 100%; /* Imagen responsive */
    height: auto;    /* Mantiene la proporción */
    border-radius: 5px;
}

/* Estilo para el enlace de detalles */
.propiedad a {
    display: inline-block;
    margin-top: 10px;
    padding: 10px 15px;
    background-color: #007BFF;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

/* Efecto al pasar el mouse sobre el enlace */
.propiedad a:hover {
    background-color: #0056b3;
}

</style>
<body>
    <h1>Listado de Propiedades</h1>
    <div class="catalogo-propiedades">
        <?php if (isset($propiedades) && count($propiedades) > 0): ?>
            <?php foreach ($propiedades as $propiedad): ?>
                <div class="propiedad">
                    <h2><?php echo htmlspecialchars($propiedad->nombre); ?></h2>
                    <p><?php echo htmlspecialchars($propiedad->descripcion); ?></p>
                    <p>Precio: <?php echo htmlspecialchars($propiedad->precio); ?> $</p>
                    <?php if (!empty($propiedad->imagen)): ?>
                        <img src="<?php echo htmlspecialchars($propiedad->imagen); ?>" alt="<?php echo htmlspecialchars($propiedad->nombre); ?>">
                    <?php endif; ?>
                    <a href="ruta/a/detalle.php?id=<?php echo $propiedad->id; ?>">Ver detalles</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay propiedades disponibles.</p>
        <?php endif; ?>
    </div>
</body>
</html>
