window.onload = async () => {
    let response = await fetch("php/login.php")

    if (response.ok) {
        console.log("Already logged in");
        window.location.replace("html/main.html");
    } else {
        console.log("Not logged in");
        window.location.replace("html/login.html");
    }
}
