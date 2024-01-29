


document.addEventListener("DOMContentLoaded", function() {

// Selecciona todos los elementos con la clase "accordion"
var acc = document.getElementsByClassName("accordion");

// Recorre todos los elementos y añade un evento de clic a cada uno
for (var i = 0; i < acc.length; i++) {
    acc[i].addEventListener("click", function() {
        // Alternar la clase "active" para resaltar el botón de acordeón activo
        this.classList.toggle("active");

        // Obtén el siguiente elemento hermano del botón de acordeón actual
        var panel = this.nextElementSibling;

        // Si el panel está abierto, ciérralo; de lo contrario, ábrelo
        if (panel.style.display === "block") {
            console.log("mierda");
            panel.style.display = "none";
        } else {
            console.log("caca");
            panel.style.display = "block";
        }
    });
}
});