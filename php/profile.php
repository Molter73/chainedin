<?php
/*
 * Permite interactuar con el perfil de usuario.
 *
 * GET: Devuelve el perfil del usuario.
 */

include_once("error.php");
include_once("db.php");
include_once("session.php");
include_once("utils.php");

function get_profile($id) {
    $conn = db_connect();

    $stmt = mysqli_prepare($conn, "SELECT profiles.name, surname, phone, picture, CV, email  FROM profiles INNER JOIN users ON profiles.id=users.id WHERE profiles.id=?;");
    mysqli_stmt_bind_param($stmt, "i",$id);
    if (!mysqli_stmt_execute($stmt)) {
        internal_error(DATABASE_QUERY_ERROR, mysqli_error());
    }

    $res = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($res);

    mysqli_close($conn);

    return json_encode(array(
        "error" => 0,
        "data" => $data,
    ));
}

function update_profile($name, $surname, $phone) {
    if (is_null($name) || $name === false) {
        bad_request(INVALID_ARGUMENT, "Invalid name");
    }

    if (is_null($surname) || $surname === false) {
        bad_request(INVALID_ARGUMENT, "Invalid surname");
    }

    if (is_null($phone) || $phone === false) {
        bad_request(INVALID_ARGUMENT, "Invalid phone");
    }

    $id = $_SESSION["user_id"];
    $pic = download_profile_pic($id, 'pic');

    $conn = db_connect();

    $stmt = mysqli_prepare($conn, "UPDATE profiles SET name=?, surname=?, phone=?, picture=? WHERE id=?;");
    mysqli_stmt_bind_param($stmt, "ssssi", $name, $surname, $phone, $pic, $_SESSION["user_id"]);
    if (!mysqli_stmt_execute($stmt)) {
        internal_error(DATABASE_QUERY_ERROR, mysqli_error());
    }

    mysqli_close($conn);

    return json_encode(array(
        "error" => 0,
        "msg" => "Success",
    ));
}

header("Content-Type: application/json");
validate_session();

switch($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT, [
            "options" => [
                "default" => $_SESSION["user_id"],
            ]
        ]);
        echo get_profile($id);
        break;

    case "POST":
        $name = filter_input(INPUT_POST, "name");
        $surname = filter_input(INPUT_POST, "surname");
        $phone = filter_input(INPUT_POST, "phone");

        echo update_profile($name, $surname, $phone);
        break;

    default:
    unsupported_method();
}
?>
