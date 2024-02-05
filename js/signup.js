async function subscribe(event) {
    event.preventDefault();

    const pic = document.getElementById("upload-pic");

    let form_data = new FormData();
    form_data.set("email", document.querySelector("#email").value);
    form_data.set("pass", document.querySelector("#pass").value);
    form_data.set("name", document.querySelector("#name").value);
    form_data.set("surname", document.querySelector("#surname").value);
    form_data.set("phone", document.querySelector("#phone").value);
    form_data.set("CV", document.querySelector("#CV").value);

    if (pic.files[0] != null) {
        form_data.set("pic", pic.files[0]);
    }

    let response = await fetch("../php/signup.php", {
        method: "POST",
        headers: {
            "Accept": "application/json",
        },
        body: form_data,
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

    let profile_pic = document.getElementById("profile-pic");
    let pic = document.getElementById("upload-pic");
    profile_pic.addEventListener("click", function() {
        pic.click();
    });

    pic.addEventListener("change", function() {
        profile_pic.src = URL.createObjectURL(pic.files[0]);
    });
});
