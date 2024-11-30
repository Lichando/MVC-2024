<!DOCTYPE html>
<html lang="es">

<head>
    <?= $head ?>
    <style>
        /* Contenedor principal del dashboard */
        .dashboard-container {
            display: flex;
            margin: 20px;
        }

        /* Barra lateral */
        .dashboard-sidebar {
            width: 250px;
            background-color: #f1f1f1;
            padding: 20px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .dashboard-sidebar h3 {
            font-size: 1.5em;
            margin-bottom: 15px;
        }

        .dashboard-sidebar ul {
            list-style: none;
            padding-left: 0;
        }

        .dashboard-sidebar ul li {
            margin-bottom: 10px;
        }

        .dashboard-sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-size: 1.1em;
            transition: color 0.3s;
        }

        .dashboard-sidebar ul li a:hover {
            color: #007bff;
        }

        /* Sección principal */
        .dashboard-main {
            flex-grow: 1;
            margin-left: 20px;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Estilo para el formulario */
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f8f8;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container label {
            display: block;
            margin-bottom: 5px;
        }

        .form-container input,
        .form-container textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .form-container button {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #0056b3;
        }

        .form-container p {
            text-align: center;
        }

        .form-container a {
            color: #007bff;
            text-decoration: none;
        }

        .form-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header>
        <?= $header; ?>
    </header>

    <main>
        <div class="dashboard-container">
            <!-- Barra lateral -->
            <aside class="dashboard-sidebar">
                <h3>Opciones</h3>
                <ul>
                    <li><a href="../inmobiliaria/propiedades">Ver Propiedades</a></li>
                    <!-- Redirecciona al dashboard -->
                    <li><a href="../inmobiliaria/cargar">Cargar Propiedad</a></li>
                    <!-- Redirecciona a cargar propiedad -->
                    <li><a href="logout">Cerrar Sesión</a></li> <!-- Redirecciona a cerrar sesión -->
                </ul>
            </aside>

            <!-- Contenido principal del dashboard -->
            <section class="dashboard-main">
                <div class="form-container">
                    <h2>Cargar Nueva Propiedad</h2>

                    <form method="POST">
                        <div>
                            <label> Direccion fictio de propiedad</label>
                            <input type="text" name="namefic" id="namefic">

                            <label>Valor de la propiedad</label>
                            <input type="text" name="precio" id="precio">

                            <label> Descripcion</label>
                            <textarea></textarea>
                        </div>
                        <div>
                            <input type="file" name="imagen1" id="imagen1">
                            <input type="file" name="imagen2" id="imagen2">
                            <input type="file" name="imagen3" id="imagen3">
                            <input type="file" name="imagen4" id="imagen4">
                            <input type="file" name="imagen5" id="imagen5">
                        </div>
                        <div>
                            <select name="estado" id="estado">
                                <option value="1">Venta</option>
                                <option value="2">Alquiler</option>
                                <option value="3">Alquiler temporario</option>
                                <option value="4">Reservado</option>
                                <option value="5">Vendida</option>
                            </select>
                        </div>
                        <div>
                            <h1>Búsqueda de Ubicaciones</h1>
                            <form id="location-form">
                                <input type="text" id="search" placeholder="Escribe una ubicación..."
                                    autocomplete="off">
                                <div id="results"></div>
                            </form>
                        </div>

                        <!-- Para capturar el ID del usuario, pasamos esta variable desde el controlador -->
                        <input type="text" name="user_id" value="<?php echo $inmobiliariaId; ?>">

                        <button type="submit">Crear Propiedad</button>
                    </form>

                    <p><a href="../inmobiliaria/propiedades">Ver lista de propiedades</a></p>
                </div>
            </section>
        </div>
    </main>

    <footer>
        <?= $footerDash; ?>
    </footer>
    <script>
    const searchInput = document.getElementById('search');
    const resultsDiv = document.getElementById('results');
    const locationForm = document.getElementById('location-form');
    let debounceTimeout; // Variable para el temporizador del debouncing

    // Función para buscar en Nominatim
    async function searchLocations(query) {
      if (query.length < 3) { // Esperar al menos 3 caracteres
        resultsDiv.innerHTML = '';
        return;
      }
      const url = `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&limit=5`;

      try {
        const response = await fetch(url);
        const results = await response.json();
        displayResults(results);
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    }

    // Función para mostrar los resultados
    function displayResults(results) {
      resultsDiv.innerHTML = ''; // Limpiar resultados previos
      if (results.length === 0) {
        resultsDiv.innerHTML = '<p>No se encontraron resultados</p>';
        return;
      }

      results.forEach(result => {
        const div = document.createElement('div');
        div.className = 'result';
        div.textContent = `${result.display_name}`;
        div.dataset.lat = result.lat;
        div.dataset.lon = result.lon;

        // Agregar evento para seleccionar una ubicación
        div.addEventListener('click', () => {
          alert(`Seleccionaste: ${result.display_name}\nLatitud: ${result.lat}\nLongitud: ${result.lon}`);
        });

        resultsDiv.appendChild(div);
      });
    }

    // Función de debouncing
    function debounce(func, delay) {
      return function (...args) {
        clearTimeout(debounceTimeout);
        debounceTimeout = setTimeout(() => func(...args), delay);
      };
    }

    // Prevenir el envío del formulario al presionar "Enter"
    locationForm.addEventListener('submit', (event) => {
      event.preventDefault();
    });

    // Vincular la función de búsqueda con debouncing
    const debouncedSearch = debounce(searchLocations, 300);

    // Escuchar los cambios en el campo de búsqueda
    searchInput.addEventListener('input', () => {
      const query = searchInput.value.trim();
      debouncedSearch(query);
    });
  </script>
</body>

</html>