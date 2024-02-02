$("#login").on("submit", function(event) {
    console.log("Caught event")
    console.log({
        "email": $("#email").val(),
        "pass": $("#pass").val()
    })
    $.post("../php/login.php", {
        "email": $("#email").val(),
        "pass": $("#pass").val()
    }, function(data) {
            console.log("Success")
            window.location.replace("../html/main.html")
        })
        .fail(function(data) {
            console.log("Failure")
            console.log(data)
        })
    event.preventDefault()
})
