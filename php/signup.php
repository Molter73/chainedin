<?php
/**
 * Crea un usuario
 *
 * Acepta método POST con los siguientes parámetros:
    * - name
    * - email
    * - pass
 *
 * INSERT INTO users (name, email, pass) VALUES (...);
 */
include_once("error.php");
include_once("db.php");

function signup($name, $email, $pass) {
    if (is_null($name) || $name === false) {
        bad_request(INVALID_ARGUMENT, "Invalid name");
    }

    if (is_null($email) || $email === false) {
        bad_request(INVALID_ARGUMENT, "Invalid email");
    }

    if (is_null($pass) || $pass === false) {
        bad_request(INVALID_ARGUMENT, "Invalid pass");
    }

    $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);

    $conn = db_connect();

    $stmt = mysqli_prepare($conn, "INSERT INTO users(name, email, pass) VALUES (?, ?, ?);");
    mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hashed_pass);
    if (!mysqli_stmt_execute($stmt)) {
        internal_error(
            DATABASE_QUERY_ERROR,
            "Failed to create new user - " . mysqli_error($conn)
        );
    }

    return json_encode(array(
        "error" => 0,
        "msg" => "User created successfully",
    ));
}

switch($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        $name = filter_input(INPUT_POST, "name");
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        $pass = filter_input(INPUT_POST, "pass");
        echo signup($name, $email, $pass);
        break;

    default:
        die("Usupported method");
}
?>
