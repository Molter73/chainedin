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
include_once("user_types.php");

function get_applications($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT id, title, company, logo FROM jobs INNER JOIN applicants ON jobs.id = applicants.job_id WHERE applicants.user_id=?;");
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        internal_error(DATABASE_QUERY_ERROR, mysqli_error());
    }

    $res = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res, MYSQLI_ASSOC);
}

function get_profile($id) {
    $conn = db_connect();

    $stmt = mysqli_prepare($conn, "SELECT name, surname, phone, picture, CV, email, type FROM profiles INNER JOIN users ON profiles.id=users.id WHERE profiles.id=?;");
    mysqli_stmt_bind_param($stmt, "i",$id);
    if (!mysqli_stmt_execute($stmt)) {
        internal_error(DATABASE_QUERY_ERROR, mysqli_error());
    }

    $res = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($res);

    $data["type"] = user_type_to_string($data["type"]);

    if ($id == $_SESSION["user_id"]) {
        $data['applications'] = get_applications($conn, $id);
    }

    mysqli_close($conn);

    return json_encode(array(
        "error" => 0,
        "data" => $data,
    ));
}

function update_profile($name, $surname, $phone, $CV) {
    if (is_null($name) || $name === false) {
        bad_request(INVALID_ARGUMENT, "Invalid name");
    }

    if (is_null($surname) || $surname === false) {
        bad_request(INVALID_ARGUMENT, "Invalid surname");
    }

    if (is_null($phone) || $phone === false) {
        bad_request(INVALID_ARGUMENT, "Invalid phone");
    }

    if (is_null($CV) || $CV === false) {
        bad_request(INVALID_ARGUMENT, "Invalid CV");
    }

    $id = $_SESSION["user_id"];
    $pic = download_profile_pic($id, 'pic');

    $conn = db_connect();

    $stmt = mysqli_prepare($conn, "UPDATE profiles SET name=?, surname=?, phone=?, picture=IFNULL(?, picture), CV=? WHERE id=?;");
    mysqli_stmt_bind_param($stmt, "sssssi", $name, $surname, $phone, $pic, $CV, $_SESSION["user_id"]);
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
        $CV = filter_input(INPUT_POST, "CV");

        echo update_profile($name, $surname, $phone, $CV);
        break;

    default:
    unsupported_method();
}
?>
