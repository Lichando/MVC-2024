<!DOCTYPE html>
<html lang="es">

<head>
  <?= $head ?>
</head>

<body>
  <header>
    <?= $header ?>
  </header>

  <main>
    <section id="contacto" class="contacto">
      <div class="container-contacto">
        <h1>Contáctanos de manera fácil</h1>

        <p>Elige una de las siguientes opciones para ponerte en contacto con nosotros. ¡Nos encantaría ayudarte!</p>

        <div class="contact-options">
          <div class="option">
            <a href="https://wa.me/1111111" target="_blank" class="contact-link">
              <i class="fab fa-whatsapp fa-2x"></i>
              <span>Enviar un mensaje vía WhatsApp</span>
            </a>
          </div>

          <div class="option">
            <a href="mailto:contacto@greenhouse.com" target="_blank" class="contact-link">
              <i class="fas fa-envelope fa-2x"></i>
              <span>Envíanos un correo electrónico</span>
            </a>
          </div>

          <div class="option">
            <a href="https://www.instagram.com/greenhouse" target="_blank" class="contact-link">
              <i class="fab fa-instagram fa-2x"></i>
              <span>Síguenos en Instagram</span>
            </a>
          </div>
        </div>

        <h3>Ubícanos:</h3>
        <p><strong>Dirección:</strong>  Av.Carballo 100 ,Rosario, Santa Fe</p>


        <!-- Widget de chat en vivo -->
        <h3>¿Necesitas ayuda inmediata?</h3>
        <p>Puedes escribirnos por nuestro chat en vivo. Estamos disponibles para ayudarte.</p>

        <!-- Botón para abrir el chat en vivo -->
        <a href="https://chatwidget.example.com" class="btn-chat">
          <i class="fas fa-comments fa-2x"></i> ¡Chatea con nosotros!
        </a>
      </div>
    </section>
  </main>

  <footer id="footer-box">
    <?= $footer ?>
  </footer>

  <?= $scripts ?>
</body>

</html>
