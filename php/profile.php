<?php
/*
 * Permite interactuar con el perfil de usuario.
 *
 * GET: Devuelve el perfil del usuario.
 */

include_once("error.php");
include_once("db.php");
include_once("session.php");

function get_profile() {
    $conn = db_connect();

    $stmt = mysqli_prepare($conn, "SELECT profiles.name, surname, phone, picture, CV, email  FROM profiles INNER JOIN users ON profiles.id=users.id WHERE profiles.id=?;");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION["user_id"]);
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

header("Content-Type: application/json");
validate_session();

switch($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        echo get_profile();
        break;

    default:
    unsupported_method();
}
?>
