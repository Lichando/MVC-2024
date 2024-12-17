<!--Slider Inicio!-->

<script>
  let currentSlide = 0;
  const slides = document.querySelectorAll('.slide');

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.style.display = (i === index) ? 'block' : 'none';
    });
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  }

  setInterval(nextSlide, 3000); // Cambia de imagen cada 3 segundos
  showSlide(currentSlide);

</script>

<script>
  document.addEventListener("DOMContentLoaded", function () {
    const track = document.querySelector(".inmobiliarias-track");
    let currentTranslate = 0;  // Comienza desde 0 para que la animación empiece desde el inicio
    let lastUpdateTime = 0;
    let moveSpeed = 5; // Aumenta este valor para hacerlo más rápido (ajústalo según el resultado)

    // Función para la animación continua
    function autoMove(timestamp) {
      if (!lastUpdateTime) lastUpdateTime = timestamp; // Establece el tiempo inicial

      const deltaTime = timestamp - lastUpdateTime;
      lastUpdateTime = timestamp;

      // Desplazar suavemente
      currentTranslate -= moveSpeed * (deltaTime / 16.67); // Este valor ahora mueve más rápido

      // Si el desplazamiento se sale del contenedor, reiniciamos el desplazamiento
      if (currentTranslate <= -track.offsetWidth) {
        currentTranslate = 0; // Reiniciar cuando llegue al final
      }

      track.style.transform = `translateX(${currentTranslate}px)`;
      requestAnimationFrame(autoMove); // Llamada recursiva para animación continua
    }

    // Iniciar la animación automática
    requestAnimationFrame(autoMove);
  });
</script>
<script>
  // Añadir un evento al formulario para interceptar el submit
  document.getElementById('form-busqueda').addEventListener('submit', function (event) {
    event.preventDefault();  // Evita que el formulario se envíe de forma tradicional

    // Crear un formulario dinámico para enviar los datos mediante POST
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = '#PATH#propiedades/lista';  // Ajusta la URL a la correcta

    // Recoger los valores del formulario
    var tipoBusqueda = document.getElementById('tipo-busqueda').value;
    var cantidadBanos = document.getElementById('cantidad-banos').value;
    var cantidadDormitorios = document.getElementById('cantidad-dormitorios').value;
    var direccion = document.getElementById('direccion').value;

    // Crear campos ocultos para enviar los datos con POST
    if (tipoBusqueda) {
      var input1 = document.createElement('input');
      input1.type = 'hidden';
      input1.name = 'tipo-busqueda';
      input1.value = tipoBusqueda;
      form.appendChild(input1);
    }
    if (cantidadBanos) {
      var input3 = document.createElement('input');
      input3.type = 'hidden';
      input3.name = 'cantidad-banos';
      input3.value = cantidadBanos;
      form.appendChild(input3);
    }
    if (cantidadDormitorios) {
      var input4 = document.createElement('input');
      input4.type = 'hidden';
      input4.name = 'cantidad-dormitorios';
      input4.value = cantidadDormitorios;
      form.appendChild(input4);
    }
    if (direccion) {  // Asegúrate de que la variable 'calle' esté correctamente declarada
      var input5 = document.createElement('input');
      input5.type = 'hidden';
      input5.name = 'direccion';
      input5.value = direccion;
      form.appendChild(input5);
    }

    // Añadir el formulario al documento y enviarlo
    document.body.appendChild(form);
    form.submit();  // Ahora envía el formulario después de añadir los campos ocultos
  });
</script>
<script>
document.getElementById('menu-icon').addEventListener('click', function() {
    const nav = document.getElementById('nav');
    const headerContainer = document.getElementById('header-container');
    
    // Alternar el estado del menú (mostrar/ocultar)
    nav.classList.toggle('active');
    
    // Alternar la clase que mueve el logo
    headerContainer.classList.toggle('logo-move');
});



</script>

<script>
  // Activar o desactivar el menú cuando se hace clic en "Opciones"
document.getElementById('menu-toggle').addEventListener('click', function () {
    const menu = document.getElementById('menusegundo');
    menu.classList.toggle('active');
});

</script>