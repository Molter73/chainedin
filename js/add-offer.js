async function new_offer(e) {
    e.preventDefault();

    console.log(e);
    const title = document.getElementById("postTitle").value;
    const company = document.getElementById("postCompany").value;
    const description = document.getElementById("postDescription").value;

    let response = await fetch("../php/jobs.php", {
        method: "POST",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
            title: title,
            company: company,
            description: description,
        }),
    });

    if (response.ok) {
        window.location.href = "../index.html";
    }

    alert("error");
}

document.addEventListener("DOMContentLoaded", async function() {
    let add_offer = document.getElementById("add-offer");

    console.log(add_offer);
    add_offer.addEventListener("submit", new_offer);
})
