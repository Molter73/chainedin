async function update_navbar() {
    let response = await fetch("../php/profile.php");
    console.log(response);
    if (!response.ok) {
        return;
    }

    response = await response.json();
    let data = response.data;
    console.log(response);

    let profile = document.getElementById("nav-profile");

    document.getElementById("username").textContent = data.name;

    if (data.picture != null) {
        let pic = profile.querySelector('img');
        pic.src = data.picture;
    }
}

document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("profile-dropdown").addEventListener('click', async function(event) {
        window.location.href = "profile.html";
    });

    document.getElementById("logout").addEventListener('click', async function(event) {
        event.preventDefault();
        let response = await fetch("../php/logout.php", {
            method: "POST",
        });

        if (response.ok) {
            window.location.href = "../index.html";
        }
    });

     update_navbar();
});
