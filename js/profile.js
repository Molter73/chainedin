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
    if (data.picture != null) {
        document.getElementById("profile-pic").src = data.picture;
    }
});
