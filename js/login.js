$("#login").on("submit", function(event) {
    $.post("../php/login.php", {
        "email": $("#email").val(),
        "pass": $("#pass").val()
    }, function(data) {
            window.location.replace("../html/main.html")
        })
        .fail(function(data) {
            console.log("Failure")
            console.log(data)
        })
    event.preventDefault()
})
