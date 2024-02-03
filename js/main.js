let lastFetchedPage = 0;
let isFetching = false;

// Función para crear una oferta vacía a partir de la plantilla
function crearOfertaVacia() {
    var plantilla = document.getElementById("plantillaOferta");
    var ofertaClone = plantilla.content.cloneNode(true);
    return ofertaClone;
}

// Función para llenar la lista con ofertas
async function llenarListaOfertas() {
    const listaOfertas = document.getElementById("listaOfertas");
    let load_msg = document.getElementById("load-msg");

    isFetching = true;

    load_msg.style.display = "block";

    const datos = await fetch("../php/jobs.php?" + new URLSearchParams({
        page: lastFetchedPage,
        count: 20,
    }))
        .then((response) => response.json());

    lastFetchedPage++;

    datos.data.forEach((oferta) => {
        const ofertaVacia = crearOfertaVacia();

        plantilla = ofertaVacia.querySelector("#plantilla");
        ofertaVacia.querySelector(".name").textContent = oferta.company;
        ofertaVacia.querySelector(".oferta").textContent = oferta.title;
        plantilla.setAttribute("id", oferta.id);
        plantilla.addEventListener("click", (event) => {
            target = event.target.classList.contains("plantilla") ?
                event.target : event.target.closest("div")

            // Vamos a la oferta con el id correspondiente
            window.location.href = "offer.html?" + new URLSearchParams({
                id: target.id,
            })
        });

        listaOfertas.append(ofertaVacia);
    });

    load_msg.style.display = "none";
    isFetching = false;
}

document.addEventListener("DOMContentLoaded", function() {
    const fetchTrigger = document.getElementById("fetchTrigger");
    const options = {
        rootMargin: "0px",
        threshold: 0
    };

    const observer = new IntersectionObserver((entries, observer) => {
        const { isIntersecting } = entries[0];

        if (isIntersecting && !isFetching) {
            llenarListaOfertas();
        }
    }, options);
    observer.observe(fetchTrigger);
});
