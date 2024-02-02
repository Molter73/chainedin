let lastFetchedPage = 0;
let isFetching = false;

// Función para crear una oferta vacía a partir de la plantilla
function crearOfertaVacia() {
    var plantilla = document.getElementById("plantillaOferta");
    var ofertaClone = plantilla.content.cloneNode(true);
    return ofertaClone;
}

// Función para obtener datos simulados del servidor
function obtenerDatosDelServidor(page) {
    return new Promise((resolve) => {
        setTimeout(() => {
            // Aquí podrías obtener datos reales del servidor
            var newData = [
                { titulo: "Oferta 1", descripcion: "Descripción de la oferta 1", precio: "$10" },
                { titulo: "Oferta 2", descripcion: "Descripción de la oferta 2", precio: "$20" },
                { titulo: "Oferta 3", descripcion: "Descripción de la oferta 3", precio: "$30" },
                { titulo: "Oferta 4", descripcion: "Descripción de la oferta 1", precio: "$10" },
                { titulo: "Oferta 5", descripcion: "Descripción de la oferta 2", precio: "$20" },
                { titulo: "Oferta 6", descripcion: "Descripción de la oferta 3", precio: "$30" },
                { titulo: "Oferta 7", descripcion: "Descripción de la oferta 1", precio: "$10" },
                { titulo: "Oferta 8", descripcion: "Descripción de la oferta 2", precio: "$20" },
                { titulo: "Oferta 9", descripcion: "Descripción de la oferta 3", precio: "$30" },
                { titulo: "Oferta 10", descripcion: "Descripción de la oferta 3", precio: "$30" }
            ];
            resolve(newData);
        }, 1000);
    });
}

// Función para llenar la lista con ofertas
async function llenarListaOfertas() {
    const listaOfertas = document.querySelector("#listaOfertas");
    const loader = document.querySelector("#loader");

    isFetching = true;

    const datos = await fetch("../php/jobs.php?" + new URLSearchParams({
        page: lastFetchedPage,
        count: 20,
    }))
        .then((response) => response.json());

    lastFetchedPage++;
    console.log(datos);

    datos.data.forEach((oferta) => {
        const ofertaVacia = crearOfertaVacia();

        ofertaVacia.querySelector(".name").textContent = oferta.company;
        ofertaVacia.querySelector(".oferta").textContent = oferta.title;
        ofertaVacia.querySelector(".detalles").textContent = oferta.description;

        listaOfertas.insertBefore(ofertaVacia, loader);
    });

    isFetching = false;
}

// Función de devolución de llamada para el observador de intersección
function callback(entries, observer) {
    const { isIntersecting } = entries[0];

    if (isIntersecting && !isFetching) {
        llenarListaOfertas();
    }

}

document.addEventListener("DOMContentLoaded", function() {
    const fetchTrigger = document.querySelector("#fetchTrigger");

    const options = {
        rootMargin: "0px",
        threshold: 0
    };
    const observer = new IntersectionObserver(callback, options);
    observer.observe(fetchTrigger);
});
