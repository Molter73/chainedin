<?php
/*
 * Permite interactuar con la tabla de trabajos basado en el método HTTP
 * utilizado.
 *
 * POST: Crea un nuevo trabajo. Parámetros:
 * - title
 * - description
 * - company
 * - logo (opcional)
 *
 * GET: Devuelve una lista de trabajos. Parámetros:
 * - count: Cantidad de trabajos a devolver por página (default: 20)
 * - page: Página a devolver (default: 0)
 */

include_once("error.php");
include_once("db.php");
include_once("session.php");

function get_job($id, $count, $page) {
    $conn = db_connect();

    $stmt = mysqli_prepare($conn, "SELECT * FROM jobs WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        internal_error(DATABASE_QUERY_ERROR, mysqli_error());
    }

    $res = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($res);

    // Get applicants for job
    $offset = $count * $page;
    $stmt = mysqli_prepare(
        $conn,
        "SELECT profiles.name, email, picture FROM users INNER JOIN applicants ON users.id = applicants.user_id INNER JOIN profiles ON users.id = profiles.id WHERE applicants.job_id = ? LIMIT ?, ?"
    );
    mysqli_stmt_bind_param($stmt, "iii", $id, $offset, $count);

    if (!mysqli_stmt_execute($stmt)) {
        internal_error(DATABASE_QUERY_ERROR, mysqli_error());
    }

    $res = mysqli_stmt_get_result($stmt);
    $applicants = mysqli_fetch_all($res, MYSQLI_ASSOC);

    mysqli_close($conn);

    $data["applicants"] = $applicants;

    return json_encode(array(
        'error' => 0,
        'data' => $data,
    ));
}

function get_jobs($count, $page) {
    $offset = $page * $count;

    $conn = db_connect();

    $stmt = mysqli_prepare($conn, "SELECT id, title, company, logo FROM jobs ORDER BY creation_date DESC LIMIT ?,?");
    mysqli_stmt_bind_param($stmt, "ii", $offset, $count);
    if (!mysqli_stmt_execute($stmt)) {
        internal_error(DATABASE_QUERY_ERROR, mysqli_error());
    }

    $res = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);

    // Get count of jobs
    $res = mysqli_query($conn, "SELECT COUNT(*) FROM jobs");
    $total = (int)mysqli_fetch_column($res);

    mysqli_close($conn);

    $response = (object) [
        'error' => 0,
        'metadata' => (object) [
            'page' => $page,
            'entries' => count($rows),
            'total_entries' => $total,
        ],
        'data' => $rows,
    ];

    return json_encode($response);
}

function add_job_logo($conn) {
    $allowed_formats = array("jpg", "jpeg", "png");
    $filename = $_FILES["logo"]["name"];

    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!in_array($ext, $allowed_formats)) {
        return false;
    }

    $id = mysqli_insert_id($conn);
    $output_dir = "../imgs/jobs/$id";

    // Create directory if it doesn't exist
    if (!is_dir($output_dir)) {
        !mkdir($output_dir, 0777, true);
    }
    $output_path = "$output_dir/logo.$ext";

    if (!move_uploaded_file($_FILES["logo"]["tmp_name"], $output_path)) {
        return false;
    }

    $stmt = mysqli_prepare($conn, "UPDATE jobs SET logo=? WHERE id=?;");
    mysqli_stmt_bind_param($stmt, "si", $output_path, $id);
    if (!mysqli_stmt_execute($stmt)) {
        return false;
    }

    return true;
}

function add_job($title, $description, $company) {
    if (is_null($title) || $title === false) {
        bad_request(INVALID_ARGUMENT, "Invalid title");
    }

    if (is_null($description) || $description === false) {
        bad_request(INVALID_ARGUMENT, "Invalid description");
    }

    if (is_null($company) || $company === false) {
        bad_request(INVALID_ARGUMENT, "Invalid company");
    }

    $conn = db_connect();

    $stmt = mysqli_prepare($conn, "INSERT INTO jobs(title, description, company, creation_date) VALUES (?, ?, ?, ?);");
    $creation_date = date("Y-m-d");
    mysqli_stmt_bind_param($stmt, "ssss", $title, $description, $company, $creation_date);
    if (!mysqli_stmt_execute($stmt)) {
        internal_error(DATABASE_QUERY_ERROR, mysqli_error());
    }

    // Check if a logo has been uploaded for the job posting
    if ($_FILES['logo']['size'] != 0) {
        add_job_logo($conn);
    }

    mysqli_close($conn);

    return json_encode(array(
        "error" => 0,
        "msg" => "Job added",
    ));
}

header("Content-Type: application/json");
validate_session();

switch($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $count = filter_input(INPUT_GET, "count", FILTER_VALIDATE_INT, [
            "options" => [
                "default" => 20
            ]]);
        $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT, [
            "options" => [
                "default" => 0
            ]]);

        $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

        if (is_null($id)) {
            echo get_jobs($count, $page);
        } else {
            echo get_job($id, $count, $page);
        }
        break;

    case "POST":
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $company = filter_input(INPUT_POST, "company");

        echo add_job($title, $description, $company);
        break;
    default:
        unsupported_method();
}
?>
