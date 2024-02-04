async function update_profile(event) {
    event.preventDefault();

    const name = document.getElementById("postName").value;
    const surname = document.getElementById("postSurname").value;
    const phone = document.getElementById("postPhone").value;

    let form_data = new FormData();
    form_data.set("name", name);
    form_data.set("surname", surname);
    form_data.set("phone", phone);

    let response = await fetch("../php/profile.php", {
        method: "POST",
        headers: {
            "Accept": "application/json",
        },
        body: form_data,
    });

    if (response.ok) {
        window.location.href = "../html/profile.html";
    }

    alert("error");
}

document.addEventListener("DOMContentLoaded", async function() {
    let response = await fetch("../php/profile.php");

    console.log(response);

    if (!response.ok) {
        window.location.href = "../index.html";
        return
    }

    response = await response.json();
    data = response.data;

    console.log(response);

    document.getElementById("postName").value = data.name;
    document.getElementById("postSurname").value = data.surname;
    document.getElementById("postPhone").value = data.phone;

    document.getElementById("edit-profile").addEventListener("submit", update_profile);
    document.getElementById("back").addEventListener("click", function() {
        window.location.href = "../html/profile.html";
    });
});

