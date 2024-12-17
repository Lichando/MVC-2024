<!DOCTYPE html>
<html lang="es">

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
          <img src="../img/imagenslider1.webp" alt="Imagen 1">
          <!-- Cambia la ruta de la imagen -->
        </div>
        <div class="slide">
          <img src="../img/imagenslider2.webp" alt="Imagen 2">
          <!-- Cambia la ruta de la imagen -->
        </div>
        <div class="slide">
          <img src="../img/imagenslider3.webp" alt="Imagen 3"> <!-- Cambia la ruta de la imagen -->
        </div>
      </div>

      <div class="buscador">
        <form id="form-busqueda" method="POST"> <!-- Aquí defines la URL de destino -->
          <select id="tipo-busqueda" name="tipo-busqueda">
            <option value="">¿Qué deseas hacer?</option>
            <option value="1">Comprar</option>
            <option value="2">Alquilar</option>
            <option value="3">Temporario</option>
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

          <input type="text" id="direccion" name="direccion" placeholder="Ingrese la calle">

          <button type="submit">Buscar</button>
        </form>
      </div>
    </div>

    <section class="estadisticas">
      <h2>Estadísticas de la Plataforma</h2>
      <div class="estadistica">
        <div class="numero"><?= htmlspecialchars($contadorInmo); ?></div>
        <div class="texto">Inmobiliarias 🏢</div>
      </div>
      <div class="estadistica">
        <div class="numero"><?= htmlspecialchars($contadorUser); ?></div>
        <div class="texto">Usuarios Registrados 👥</div>
      </div>
      <div class="estadistica">
        <div class="numero"><?= htmlspecialchars($contadorPropiedades); ?></div>
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
    <section class="propiedades-destacadas">
      <h2>Nuevas propiedades 🏠</h2>
      <div class="propiedades-container">
        <?php if (!empty($propiedades_mostrar)): ?>
          <?php foreach ($propiedades_mostrar as $propiedad): ?>
            <article class="propiedad">
              <!-- Condicional para la imagen -->
              <img
                src="<?= $propiedad->img1 ? '../' . htmlspecialchars($propiedad->img1) : 'https://static.vecteezy.com/system/resources/previews/019/787/070/non_2x/no-photos-and-no-phones-forbidden-sign-on-transparent-background-free-png.png' ?>"
                alt="Imagen de Propiedad">
              <div class="propiedad-info">
                <h3 class="propiedad-titulo"><?= htmlspecialchars($propiedad->direccionFake) ?></h3>
                <p class="propiedad-descripcion"><?= htmlspecialchars(strlen($propiedad->descripcion) > 50 ? substr($propiedad->descripcion, 0, 50) . '...' : $propiedad->descripcion); ?>
                </p>
                <p class="propiedad-precio"><strong>Precio:</strong> <?= htmlspecialchars($propiedad->precio) ?> USD</p>
                <a href="../propiedades/detalles/<?= urlencode(base64_encode($propiedad->id)) ?>" class="ver-detalles">Ver detalles</a>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No hay propiedades destacadas en este momento.</p>
        <?php endif; ?>
      </div>
    </section>
    </div>
    </section>
    <section class="inmobiliarias-section">
      <h2>Inmobiliarias que operan en la plataforma</h2>
      <div class="inmobiliarias-wrapper">
        <div class="inmobiliarias-track">
          <?php if (!empty($inmobiliarias)): ?>
            <?php foreach ($inmobiliarias as $inmobiliaria): ?>
              <div class="inmobiliaria-item">
                <?php if (!empty($inmobiliaria['imagen'])): ?>
                  <img src="<?= htmlspecialchars('../logosinmb/' . $inmobiliaria['imagen']); ?>"
                    alt="Inmobiliaria <?= htmlspecialchars($inmobiliaria['nombre'] ?? 'Desconocida'); ?>">
                <?php else: ?>
                  <img
                    src="https://static.vecteezy.com/system/resources/previews/019/787/070/non_2x/no-photos-and-no-phones-forbidden-sign-on-transparent-background-free-png.png"
                    alt="Inmobiliaria <?= htmlspecialchars($inmobiliaria['nombre'] ?? 'Desconocida'); ?>">
                <?php endif; ?>
                <p><?= htmlspecialchars($inmobiliaria['nombre'] ?? 'Desconocida'); ?></p>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>No hay inmobiliarias disponibles.</p>
          <?php endif; ?>
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