$("#signup").on("submit", function(event) {
    $.post("../php/signup.php", {
        "email": $("#email").val(),
        "pass": $("#pass").val(),
        "name": $("#name").val(),
    }, function(data) {
            window.location.replace("../html/main.html")
        })
        .fail(function(data) {
            d = data.responseJSON
            $("#error-msg").text(`Error ${d.error}: ${d.msg}`)
            $("#error-box").show()
            setInterval(function() {
                $("#error-box").fadeOut()
            }, 2000)
            console.log("Failure")
            console.log(data)
        })
    event.preventDefault()
})

