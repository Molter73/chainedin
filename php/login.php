<?php
/*
 * Comprueba que coincidan usuario y contraseña de la petición con lo almacena
 * en la db. Si coinciden, agrega una cookie para uso en el frontend.
 *
 * Parámetros:
 * - email
 * - pass
 */
include_once("error.php");
include_once("db.php");
include_once("session.php");

function login($email, $pass) {
    if (is_null($email) || $email === false) {
        bad_request(INVALID_ARGUMENT, "Invalid email");
    }

    if (is_null($pass) || $pass === false) {
        bad_request(INVALID_ARGUMENT, "Invalid pass");
    }

    $conn = db_connect();

    $stmt = mysqli_prepare($conn, "SELECT pass, id FROM users WHERE email=?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    if (!mysqli_stmt_execute($stmt)) {
        internal_error(
            DATABASE_QUERY_ERROR,
            "Failed to execute login query: " . mysqli_error()
        );
    }

    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_row($res);
    if (is_null($row)) {
        unauthorized_access(LOGIN_FAILED, "Email not found");
    }

    if ($row === false) {
        internal_error(DATABASE_QUERY_ERROR, mysqli_error($conn));
    }

    if (!password_verify($pass, $row[0])) {
        unauthorized_access(LOGIN_FAILED, "Invalid password");
    }

    mysqli_close($conn);
    session_start();

    $_SESSION["user_id"] = $row[1];
    return json_encode(array(
        "error" => 0,
        "msg" => "Logged in",
    ));
}

header("Content-Type: application/json");

switch($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        $pass = filter_input(INPUT_POST, "pass");
        echo login($email, $pass);
        break;

    case "GET":
        validate_session();
        echo json_encode(array(
            "error" => 0,
            "msg" => "Logged in",
        ));
        break;

    default:
        unsupported_method();
}

?>
