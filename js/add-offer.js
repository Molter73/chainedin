async function new_offer(e) {
    e.preventDefault();

    console.log(e);
    const title = document.getElementById("postTitle").value;
    const company = document.getElementById("postCompany").value;
    const description = document.getElementById("postDescription").value;
    const logo = document.getElementById("postLogo");

    let form_data = new FormData();
    form_data.set('title', title);
    form_data.set('company', company);
    form_data.set('description', description);
    form_data.set('logo', logo.files[0]);

    let response = await fetch("../php/jobs.php", {
        method: "POST",
        headers: {
            "Accept": "application/json",
        },
        body: form_data,
    });

    if (response.ok) {
        window.location.href = "../index.html";
    }

    alert("error");
}

document.addEventListener("DOMContentLoaded", async function() {
    let add_offer = document.getElementById("post-offer");

    console.log(add_offer);
    add_offer.addEventListener("submit", new_offer);
})
