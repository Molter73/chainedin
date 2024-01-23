<?php
/*
 * Comprueba que coincidan usuario y contraseña de la petición con lo almacena
 * en la db. Si coinciden, agrega una cookie para uso en el frontend.
 *
 * Parámetros:
 * - email
 * - pass
 */
include_once("db.php");

session_start();

function login($email, $pass) {
    if (is_null($email) || $email === false) {
        die("Invalid email");
    }

    if (is_null($pass) || $pass === false) {
        die("Invalid pass");
    }

    $conn = db_connect();
    if ($conn == null) {
        die("Failed to connect to DB");
    }

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email=? AND pass=?");
    mysqli_stmt_bind_param($stmt, "ss", $email, $pass);
    if (!mysqli_stmt_execute($stmt)) {
        die("Failed to execute login query");
    }

    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_row($res);
    if ($row == null) {
        die("Invalid credentials");
    }

    $_SESSION["username"] = $row["name"];
    header("location: html/jobs.html");
    echo "Logged in";
}

switch($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        $pass = filter_input(INPUT_POST, "pass");
        login($email, $pass);
        break;
    default:
        die("Unsopported method");
}

?>
