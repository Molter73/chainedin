async function subscribe() {
    let params = new URLSearchParams(window.location.search);
    let id = params.get("id");

    let body = new FormData();
    body.set("job_id", id);
    let response = await fetch("../php/apply.php",{
        method: "POST",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
            "job_id": id,
        }),
    })

    if (response.ok) {
        window.location.href = "../html/profile.html";
    }

    response = await response.json();
    let error_box = new bootstrap.Modal(document.querySelector("#error-box"));
    let error_msg = document.querySelector("#error-msg");
    error_msg.innerText = `Error ${response.error}: ${response.msg}`;
    error_box.show();
    setInterval(function() {
        error_box.hide();
    }, 3000);
}

function goto_profile(id) {
    // Vamos a la oferta con el id correspondiente
    window.location.href = "profile.html?" + new URLSearchParams({
        id: id,
    })
}

function fill_offer(data) {
    let name = document.getElementById("nombre-empresa");
    name.textContent = data.company;

    let opening = document.getElementById("puesto");
    opening.textContent = data.title;

    let description = document.getElementById("descripcion");
    description.textContent = data.description;

    let logo = document.getElementById("logo-empresa");
    if (data.logo != null) {
        logo.src = data.logo;
    } else {
        logo.src = "../assets/icons8-company-64.png";
    }

    let applicants_list = document.getElementById("applicants");
    if (data.applicants != null) {
        document.getElementById("applicants-title").style.display = "";
        data.applicants.forEach((applicant) => {
            let template = document.getElementById("applicant-template");
            let applicant_node = template.content.cloneNode(true);

            applicant_node.getElementById("applicant-name").textContent = applicant.name;
            applicant_node.getElementById("applicant-email").textContent = applicant.email;

            if (applicant.picture != null) {
                applicant_node.getElementById("applicant-pic").src = applicant.picture;
            }

            applicant_node.getElementById('template').addEventListener("click", function() {
                goto_profile(applicant.id);
            });
            applicants_list.append(applicant_node);
        });
    }
}

document.addEventListener("DOMContentLoaded", async function() {
    let params = new URLSearchParams(window.location.search);
    let id = params.get("id");

    let response = await fetch("../php/jobs.php?" + new URLSearchParams({
        id: id,
    }))
        .then((response) => response.json());

    fill_offer(response.data);

    let subsciption_button = document.getElementById("subscribe");
    subsciption_button.addEventListener("click", subscribe);

    let back_button = document.getElementById("back");
    back_button.addEventListener("click", function() {
        window.location.href = "../index.html";
    });
});
