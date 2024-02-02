$.getJSON("php/login.php", function(data) {
    console.log("Already logged in");
    window.location.replace("html/main.html");
})
    .fail(function(data) {
        console.log("Not logged in");
        window.location.replace("html/login.html");
    })
