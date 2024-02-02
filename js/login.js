$("#login").on("submit", function(event) {
    $.post("../php/login.php", {
        "email": $("#email").val(),
        "pass": $("#pass").val()
    }, function(data) {
            window.location.href = "../index.html"
        })
        .fail(function(data) {
            d = data.responseJSON
            $("#error-msg").text(`Error ${d.error}: ${d.msg}`)
            $("#error-box").show()
            setInterval(function() {
                $("#error-box").fadeOut()
            }, 2000)
        })
    event.preventDefault()
})
