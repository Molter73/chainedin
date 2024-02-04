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

function download_profile_pic() {
    if (is_null($_FILES['pic']) || $_FILES['pic']['size'] == 0) {
        return null;
    }

    $allowed_formats = array("jpg", "jpeg", "png");
    $filename = $_FILES["pic"]["name"];

    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!in_array($ext, $allowed_formats)) {
        return null;
    }

    $id = $_SESSION["user_id"];
    $output_dir = "../imgs/profiles/$id";

    // Create directory if it doesn't exist
    if (!is_dir($output_dir)) {
        !mkdir($output_dir, 0777, true);
    }
    $output_path = "$output_dir/pic.$ext";

    if (!move_uploaded_file($_FILES["pic"]["tmp_name"], $output_path)) {
        return null;
    }

    return $output_path;
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

    $pic = download_profile_pic();

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
        echo get_profile();
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
