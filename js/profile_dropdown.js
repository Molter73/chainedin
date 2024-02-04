document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("profile").addEventListener('click', async function(event) {
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
});
