function goToOffer(id) {
    // Vamos a la oferta con el id correspondiente
    window.location.href = "offer.html?" + new URLSearchParams({
        id: id,
    })
}

function addApplications(data) {
    document.getElementById("titleOfertas").style.display = '';
    document.getElementById("listaOfertas").style.display = '';

    data.applications.forEach((oferta) => {
        const ofertaVacia = document.getElementById("plantillaOferta").content.cloneNode(true);

        plantilla = ofertaVacia.getElementById("plantilla");
        ofertaVacia.getElementById("company-name").textContent = oferta.company;
        ofertaVacia.getElementById("job-title").textContent = oferta.title;

        let logo = ofertaVacia.getElementById("company-logo");
        if (oferta.logo == null) {
            logo.src = "../assets/icons8-company-64.png";
        } else {
            logo.src = oferta.logo;
        }

        plantilla.addEventListener("click", (event) => {
            goToOffer(oferta.id);
        });

        listaOfertas.append(ofertaVacia);
    });
}

document.addEventListener("DOMContentLoaded", async function() {
    let params = new URLSearchParams(window.location.search);
    let id = params.get("id");
    let url = "../php/profile.php";

    if (id != null) {
        url += "?" + new URLSearchParams({
            id: id,
        });
    }
    let response = await fetch(url);

    console.log(response);

    if (!response.ok) {
        window.location.href = "../index.html";
        return
    }

    response = await response.json();
    data = response.data;

    console.log(response);

    document.getElementById("name").textContent = `${data.surname}, ${data.name}`;
    document.getElementById("phone").textContent = `Tel.: ${data.phone}`;
    document.getElementById("email").textContent = `Email: ${data.email}`;
    document.getElementById("cv").textContent = data.CV;
    if (data.picture != null) {
        document.getElementById("profile-pic").src = data.picture;
    }

    if (data.applications != null) {
        addApplications(data);
    }
});
