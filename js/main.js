const offers_per_page = 50;
let total_offers = 0;
let lastFetchedPage = 0;
let isFetching = false;

// Función para crear una oferta vacía a partir de la plantilla
function crearOfertaVacia() {
    var plantilla = document.getElementById("plantillaOferta");
    var ofertaClone = plantilla.content.cloneNode(true);
    return ofertaClone;
}

function goToOffer(id) {
    // Vamos a la oferta con el id correspondiente
    window.location.href = "offer.html?" + new URLSearchParams({
        id: id,
    })
}

// Función para llenar la lista con ofertas
async function llenarListaOfertas() {
    if ((offers_per_page * lastFetchedPage > total_offers) &&
        lastFetchedPage != 0) {
        // No hay más ofertas por buscar
        return;
    }

    const listaOfertas = document.getElementById("listaOfertas");
    let load_msg = document.getElementById("load-msg");

    isFetching = true;

    load_msg.style.display = "block";

    const datos = await fetch("../php/jobs.php?" + new URLSearchParams({
        page: lastFetchedPage,
        count: offers_per_page,
    }))
        .then((response) => response.json());

    lastFetchedPage++;
    total_offers = datos.metadata.total_entries;

    datos.data.forEach((oferta) => {
        const ofertaVacia = crearOfertaVacia();

        plantilla = ofertaVacia.querySelector("#plantilla");
        ofertaVacia.getElementById("company-name").textContent = oferta.company;
        ofertaVacia.getElementById("job-title").textContent = oferta.title;
        ofertaVacia.getElementById("company-logo").src = oferta.logo;
        plantilla.addEventListener("click", (event) => {
            goToOffer(oferta.id);
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
