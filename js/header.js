$(document).ready(function () {
  $(".navbar-toggler").click(function () {
    $(".navbar-collapse").toggleClass("show");
    $(".navbar-nav").toggleClass("custom-ul");
  });
});

document.addEventListener("DOMContentLoaded", function () {
  var toggleGifsButton = document.getElementById("amiguesBoton");
  var gifsPerrones = document.getElementById("gifs-perrones");

  toggleGifsButton.addEventListener("click", function () {
    if (gifsPerrones.style.display === "none") {
      gifsPerrones.style.display = "block"; // Mostrar el div
      gifsPerrones.classList.add("animated-div"); // Agregar clase para la animación
      setTimeout(function () {
        gifsPerrones.style.opacity = "1"; // Cambiar la opacidad para activar la animación
      }, 100); // Espera un pequeño tiempo antes de cambiar la opacidad
    } else {
      gifsPerrones.style.opacity = "0"; // Ocultar el div
      setTimeout(function () {
        gifsPerrones.style.display = "none"; // Cambiar la visibilidad
        gifsPerrones.classList.remove("animated-div"); // Quitar la clase para la animación
      }, 400); // Espera a que termine la animación antes de ocultar
    }
  });
});
