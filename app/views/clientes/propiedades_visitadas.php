<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>
    <style>
        /* Estilo para la lista de propiedades vistas */
        .propiedades-vistas {
            margin-top: 20px;
        }

        .propiedad-item {
            background-color: #f8f8f8;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .propiedad-item h4 {
            margin: 0;
            font-size: 1.3em;
        }

        .propiedad-item p {
            color: #555;
            font-size: 1em;
        }

        .propiedad-item a {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .propiedad-item a:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <header>
        <?= $header; ?>
    </header>

    <main>
        <div class="dashboard-container">
            <aside class="dashboard-sidebar">
                <h3>Opciones</h3>
                <ul>
                    <li><a href="dashboard">Ver Propiedades</a></li>
                    <li><a href="consultas">Consultas</a></li>
                    <li><a href="logout">Cerrar Sesión</a></li>
                </ul>
            </aside>

            <section class="dashboard-main">
                <h2>Propiedades Vistas</h2>
                <div class="propiedades-vistas">
                    <div class="propiedad-item">
                        <div>
                            <h4>Casa en Calle ABC</h4>
                            <p>Ubicación: Calle ABC, Ciudad X</p>
                            <p>Precio: $150,000</p>
                        </div>
                        <a href="ver_propiedad.php?propiedad_id=1">Ver Detalles</a>
                    </div>
                    <div class="propiedad-item">
                        <div>
                            <h4>Apartamento en Avenida XYZ</h4>
                            <p>Ubicación: Avenida XYZ, Ciudad Y</p>
                            <p>Precio: $200,000</p>
                        </div>
                        <a href="ver_propiedad.php?propiedad_id=2">Ver Detalles</a>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <?= $footerDash; ?>
    </footer>
</body>

</html>
