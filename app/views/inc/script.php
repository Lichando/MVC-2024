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
    let isDragging = false;
    let startPos = 0;
    let currentTranslate = 0;
    let prevTranslate = 0;
    let animationID;

    track.addEventListener("mousedown", dragStart);
    track.addEventListener("touchstart", dragStart);

    track.addEventListener("mouseup", dragEnd);
    track.addEventListener("touchend", dragEnd);
    track.addEventListener("mouseleave", dragEnd);
    
    track.addEventListener("mousemove", dragMove);
    track.addEventListener("touchmove", dragMove);

    function dragStart(event) {
      isDragging = true;
      startPos = getPositionX(event);
      animationID = requestAnimationFrame(animation);
      track.style.cursor = 'grabbing';
    }

    function dragEnd() {
      isDragging = false;
      cancelAnimationFrame(animationID);
      track.style.cursor = 'grab';
      prevTranslate = currentTranslate;
    }

    function dragMove(event) {
      if (isDragging) {
        const currentPosition = getPositionX(event);
        currentTranslate = prevTranslate + currentPosition - startPos;
      }
    }

    function getPositionX(event) {
      return event.type.includes("mouse") ? event.pageX : event.touches[0].clientX;
    }

    function animation() {
      track.style.transform = `translateX(${currentTranslate}px)`;
      if (isDragging) requestAnimationFrame(animation);
    }

    // Inicializar el cursor
    track.style.cursor = 'grab';
  });
</script>
