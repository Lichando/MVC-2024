<!DOCTYPE html>
<html lang="en">

<head>
  <?= $head ?>
</head>

<body>
  <header>
    <?= $header ?>
    </nav>
  </header>

  <main>
    <div class="slider-container">
      <div class="slider">
        <div class="slide">
          <img src="https://i.pinimg.com/originals/00/02/92/00029211997d8e6e5ed5f72d78ead42e.jpg" alt="Imagen 1">
          <!-- Cambia la ruta de la imagen -->
        </div>
        <div class="slide">
          <img src="https://www.serargentino.com/public/images/2020/05/Rosario-de-noche-773x458.jpeg" alt="Imagen 2">
          <!-- Cambia la ruta de la imagen -->
        </div>
        <div class="slide">
          <img src="imagen3.jpg" alt="Imagen 3"> <!-- Cambia la ruta de la imagen -->
        </div>
      </div>

      <div class="buscador">
        <form>
          <select id="tipo-busqueda" name="tipo-busqueda">
            <option value="">¿Qué deseas hacer?</option>
            <option value="comprar">Comprar</option>
            <option value="alquilar">Alquilar</option>
            <option value="emprendimientos">Buscar Emprendimientos</option>
          </select>

          <select id="tipo-propiedad" name="tipo-propiedad">
            <option value="">Tipo de Propiedad</option>
            <option value="casa">Casa</option>
            <option value="departamento">Departamento</option>
            <option value="lote">Lote</option>
            <option value="comercial">Comercial</option>
          </select>

          <select id="cantidad-banos" name="cantidad-banos">
            <option value="">Baños</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5 o más</option>
          </select>

          <select id="cantidad-dormitorios" name="cantidad-dormitorios">
            <option value="">Dormitorios</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5 o más</option>
          </select>

          <input type="text" id="calle" name="calle" placeholder="Ingrese la calle">

          <button type="submit">Buscar</button>
        </form>
      </div>
    </div>

    <section class="estadisticas">
      <h2>Estadísticas de la Plataforma</h2>
      <div class="estadistica">
        <div class="numero">120</div>
        <div class="texto">Inmobiliarias 🏢</div>
      </div>
      <div class="estadistica">
        <div class="numero">3500</div>
        <div class="texto">Usuarios Registrados 👥</div>
      </div>
      <div class="estadistica">
        <div class="numero">2500</div>
        <div class="texto">Inmuebles 🏠</div>
      </div>
    </section>

    <section class="razones-elejir">
      <h2>¿Por qué elegir nuestro broker inmobiliario?</h2>
      <div class="razones">
        <div class="razon">
          <h3>🌟 Gestión Eficiente</h3>
          <p>
            Nuestra plataforma permite una gestión mucho más eficiente en la difusión de propiedades. No solo una
            persona podrá vender su propiedad, sino que podrá consultar con diversas inmobiliarias y elegir la que más
            le convenga en base a comisiones y servicios.
          </p>
        </div>
        <div class="razon">
          <h3>💰 Comparativa de Comisiones</h3>
          <p>
            Ofrecemos una comparativa transparente de comisiones entre diferentes inmobiliarias, lo que te permite tomar
            decisiones informadas y elegir la mejor opción para ti.
          </p>
        </div>
        <div class="razon">
          <h3>🧑‍🤝‍🧑 Asesoramiento Personalizado</h3>
          <p>
            Nuestros agentes están capacitados para brindarte asesoramiento personalizado, adaptándose a tus necesidades
            y objetivos para lograr la mejor venta o alquiler de tu propiedad.
          </p>
        </div>
        <div class="razon">
          <h3>🌐 Amplia Red de Contactos</h3>
          <p>
            Contamos con una amplia red de contactos en el mercado inmobiliario, lo que aumenta las posibilidades de
            vender o alquilar tu propiedad de manera rápida y efectiva.
          </p>
        </div>
      </div>
    </section>
    <section class="emprendimientos-destacados">
      <h2>Emprendimientos Destacados 🏙️</h2>
      <div class="emprendimientos-container">
        <!-- Simulación de un foreach que selecciona los 5 más vistos -->
        <?php
        // Lista de emprendimientos con vistas
        $emprendimientos = [
          [
            'titulo' => 'Proyecto Eco Verde',
            'descripcion' => 'Un proyecto ecológico y sostenible ubicado en el corazón de la ciudad.',
            'imagen' => 'images/eco-verde.jpg',
            'link' => '#',
            'vistas' => 120
          ],
          [
            'titulo' => 'Skyline Towers',
            'descripcion' => 'Apartamentos de lujo con vistas panorámicas al skyline.',
            'imagen' => 'images/skyline.jpg',
            'link' => '#',
            'vistas' => 250
          ],
          [
            'titulo' => 'Residencial Costa Azul',
            'descripcion' => 'Un lugar paradisíaco cerca del mar con todas las comodidades.',
            'imagen' => 'images/costa-azul.jpg',
            'link' => '#',
            'vistas' => 180
          ],
          [
            'titulo' => 'Central Park Plaza',
            'descripcion' => 'Un complejo de oficinas premium en la mejor ubicación de la ciudad.',
            'imagen' => 'images/central-park.jpg',
            'link' => '#',
            'vistas' => 210
          ],
          [
            'titulo' => 'Las Torres del Sol',
            'descripcion' => 'Viviendas de alta gama en la mejor zona residencial.',
            'imagen' => 'images/torres-del-sol.jpg',
            'link' => '#',
            'vistas' => 90
          ],
          [
            'titulo' => 'Barrio Norte Residencial',
            'descripcion' => 'Una oportunidad de inversión en una zona de gran crecimiento.',
            'imagen' => 'images/barrio-norte.jpg',
            'link' => '#',
            'vistas' => 160
          ],
        ];

        // Ordenamos el array por número de vistas de mayor a menor
        usort($emprendimientos, function ($a, $b) {
          return $b['vistas'] - $a['vistas'];
        });

        // Mostramos los primeros 5 emprendimientos más vistos
        $emprendimientos_mostrar = array_slice($emprendimientos, 0, 5);
        ?>

        <!-- Mostramos los 5 más vistos -->
        <?php foreach ($emprendimientos_mostrar as $emprendimiento): ?>
          <article class="emprendimiento">
            <img src="<?= $emprendimiento['imagen'] ?>" alt="Imagen de Emprendimiento">
            <div class="emprendimiento-info">
              <h3 class="emprendimiento-titulo"><?= $emprendimiento['titulo'] ?></h3>
              <p class="emprendimiento-descripcion"><?= $emprendimiento['descripcion'] ?></p>
              <a href="<?= $emprendimiento['link'] ?>" class="emprendimiento-link">Ver más detalles</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>


    <section class="propiedades-destacadas">
      <h2>Propiedades Destacadas 🏠</h2>
      <div class="propiedades-container">
        <!-- Simulación de un foreach que selecciona las 5 propiedades más vistas -->
        <?php
        // Lista de propiedades con vistas
        $propiedades = [
          [
            'titulo' => 'Apartamento en la Playa',
            'descripcion' => 'Hermoso apartamento con vistas al mar y acceso a la playa.',
            'imagen' => 'images/playa.jpg',
            'link' => '#',
            'vistas' => 320
          ],
          [
            'titulo' => 'Casa Moderna en el Centro',
            'descripcion' => 'Casa moderna con tecnología inteligente y diseño minimalista.',
            'imagen' => 'images/centro.jpg',
            'link' => '#',
            'vistas' => 450
          ],
          [
            'titulo' => 'Residencia de Lujo en el Campo',
            'descripcion' => 'Amplia residencia rodeada de naturaleza y tranquilidad.',
            'imagen' => 'images/campo.jpg',
            'link' => '#',
            'vistas' => 290
          ],
          [
            'titulo' => 'Penthouse en el Corazón de la Ciudad',
            'descripcion' => 'Penthouse de lujo con piscina privada y vistas panorámicas.',
            'imagen' => 'images/penthouse.jpg',
            'link' => '#',
            'vistas' => 520
          ],
          [
            'titulo' => 'Chalet en Zona Residencial',
            'descripcion' => 'Chalet amplio y luminoso en una tranquila zona residencial.',
            'imagen' => 'images/chalet.jpg',
            'link' => '#',
            'vistas' => 150
          ],
          [
            'titulo' => 'Departamento Familiar en Zona Norte',
            'descripcion' => 'Departamento ideal para familias, con colegios y parques cercanos.',
            'imagen' => 'images/departamento.jpg',
            'link' => '#',
            'vistas' => 280
          ]
        ];

        // Ordenamos el array por número de vistas de mayor a menor
        usort($propiedades, function ($a, $b) {
          return $b['vistas'] - $a['vistas'];
        });

        // Mostramos los primeros 5 propiedades más vistas
        $propiedades_mostrar = array_slice($propiedades, 0, 5);
        ?>

        <!-- Mostramos las 5 propiedades más vistas -->
        <?php foreach ($propiedades_mostrar as $propiedad): ?>
          <article class="propiedad">
            <img src="<?= $propiedad['imagen'] ?>" alt="Imagen de Propiedad">
            <div class="propiedad-info">
              <h3 class="propiedad-titulo"><?= $propiedad['titulo'] ?></h3>
              <p class="propiedad-descripcion"><?= $propiedad['descripcion'] ?></p>
              <a href="<?= $propiedad['link'] ?>" class="propiedad-link">Ver más detalles</a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
    <section class="inmobiliarias-section">
      <h2>Inmobiliarias que operan en la plataforma</h2>
      <div class="inmobiliarias-wrapper">
        <div class="inmobiliarias-track">
          <?php
          // Mostrar las inmobiliarias obtenidas desde la base de datos
          if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
              echo '<div class="inmobiliaria-item">';
              echo '<img src="' . $fila['imagen'] . '" alt="Inmobiliaria ' . $fila['nombre'] . '">';
              echo '<p>' . $fila['nombre'] . '</p>';
              echo '</div>';
            }
          } else {
            echo '<p>No hay inmobiliarias disponibles.</p>';
          }
          ?>
        </div>
      </div>
    </section>

  </main>
  <footer id="footer-box">
    <?= $footer ?>
  </footer>
  </footer>
  <?= $scripts ?>
</body>

</html>