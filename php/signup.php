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
include_once("utils.php");

function signup($email, $pass, $name, $surname, $phone, $CV) {
    if (is_null($email) || $email === false) {
        bad_request(INVALID_ARGUMENT, "Invalid email");
    }

    if (is_null($pass) || $pass === false) {
        bad_request(INVALID_ARGUMENT, "Invalid pass");
    }

    if (is_null($name) || $name === false) {
        bad_request(INVALID_ARGUMENT, "Invalid name");
    }

    if (is_null($surname) || $surname === false) {
        bad_request(INVALID_ARGUMENT, "Invalid surname");
    }

    if ($phone === false) {
        bad_request(INVALID_ARGUMENT, "Invalid phone");
    }

    if ($CV === false) {
        bad_request(INVALID_ARGUMENT, "Invalid CV");
    }

    $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);

    $conn = db_connect();
    mysqli_begin_transaction($conn);

    $stmt = mysqli_prepare($conn, "INSERT INTO users(email, pass) VALUES (?, ?);");
    mysqli_stmt_bind_param($stmt, "ss", $email, $hashed_pass);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($conn);
        internal_error(
            DATABASE_QUERY_ERROR,
            "Failed to create new user - " . mysqli_error($conn)
        );
    }

    $id = mysqli_insert_id($conn);
    $pic = download_profile_pic($id, 'pic');

    $stmt = mysqli_prepare($conn, "INSERT INTO profiles(id, name, surname, phone, CV, picture) VALUES (?, ?, ?, ?, ?, ?);");
    mysqli_stmt_bind_param($stmt, "isssss", $id, $name, $surname, $phone, $CV, $pic);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_rollback($conn);
        internal_error(
            DATABASE_QUERY_ERROR,
            "Failed to create new profile - " . mysqli_error($conn)
        );
    }

    mysqli_commit($conn);
    mysqli_close($conn);

    return json_encode(array(
        "error" => 0,
        "msg" => "User created successfully",
    ));
}

header("Content-Type: application/json");

switch($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        $email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
        $pass = filter_input(INPUT_POST, "pass");
        $name = filter_input(INPUT_POST, "name");
        $surname = filter_input(INPUT_POST, "surname");
        $phone = filter_input(INPUT_POST, "phone");
        $CV = filter_input(INPUT_POST, "CV");

        echo signup($email, $pass, $name, $surname, $phone, $CV);
        break;

    default:
        die("Usupported method");
}
?>
