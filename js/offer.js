document.addEventListener("DOMContentLoaded", async function() {
    let params = new URLSearchParams(window.location.search);
    let id = params.get("id");

    let response = await fetch("../php/jobs.php?" + new URLSearchParams({
        id: id,
    }))
        .then((response) => response.json());
    let data = response.data;

    let name = document.getElementById("nombre-empresa");
    name.textContent = data.company;

    let opening = document.getElementById("puesto");
    opening.textContent = data.title;

    let description = document.getElementById("descripcion");
    description.textContent = data.description;

    let subsciption_button = document.getElementById("subscribe");
    subsciption_button.addEventListener("click", async function() {
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
            .then((response) => response.json());
        console.log(response);
    });
});
