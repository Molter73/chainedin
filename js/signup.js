async function subscribe(event) {
    event.preventDefault();

    const email = document.querySelector("#email").value;
    const pass = document.querySelector("#pass").value;
    const name = document.querySelector("#name").value;

    let response = await fetch("../php/signup.php", {
        method: "POST",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
            email: email,
            pass: pass,
            name: name,
        }),
    });

    if (response.ok) {
        window.location.href = "../index.html";
    }

    let json = await response.json();
    let error_box = new bootstrap.Modal(document.querySelector("#error-box"));
    let error_msg = document.querySelector("#error-msg");
    console.log(error_box);
    error_msg.innerText = `Error ${json.error}: ${json.msg}`;
    error_box.show();
    setInterval(function() {
        error_box.hide();
    }, 3000);
}

document.addEventListener("DOMContentLoaded", function() {
    let subscribe_form = document.querySelector("#signup");
    subscribe_form.addEventListener("submit", subscribe);
});
