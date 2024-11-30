<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>
    <style>
        /* Estilo general del formulario de inscripción */
        .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f7f7f7;
        }

        .form-box {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .form-box h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-box label {
            font-size: 1.1em;
            margin-bottom: 8px;
            display: block;
        }

        .form-box input[type="text"],
        .form-box input[type="email"],
        .form-box input[type="password"],
        .form-box select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
        }

        .form-box .btn-submit {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            width: 100%;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s;
        }

        .form-box .btn-submit:hover {
            background-color: #0056b3;
        }

        .form-box p {
            text-align: center;
            font-size: 1em;
        }

        .form-box p a {
            color: #007bff;
            text-decoration: none;
        }

        .form-box p a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header>
        <?= $header; ?>
    </header>

    <main>
        <div class="form-container">
            <div class="form-box">
                <h2>Inscripción</h2>
                <?php if (isset($error) && $error != ''): ?>
                    <div class="alert alert-danger">
                        <?= htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['successMessage'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['successMessage']; ?>
                    </div>
                    <?php unset($_SESSION['successMessage']); ?>
                <?php endif; ?>

                <form method="POST">
                    <label for="nombre">Nombre de la Inmobiliaria:</label>
                    <input type="text" name="nombre" id="nombre">

                    <label for="matricula">Matrícula:</label>
                    <input type="text" name="matricula" id="matricula">

                    <label for="direccion">Dirección:</label>
                    <input type="text" name="direccion" id="direccion">

                    <label for="telefono">Teléfono:</label>
                    <input type="text" name="telefono" id="telefono">

                    <label for="email">Correo Electrónico :</label>
                    <input type="email" name="email" id="email">

                    <button type="submit">Registrar Inmobiliaria</button>
                </form>

            </div>
        </div>
    </main>

    <footer>
        <?= $footerDash; ?>
    </footer>
</body>

</html>