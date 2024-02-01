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

function get_jobs($count, $page) {
    $offset = $page * $count;

    $conn = db_connect();

    $stmt = mysqli_prepare($conn, "SELECT * FROM jobs ORDER BY creation_date DESC LIMIT ?,?");
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
        'metadata' => (object) [
            'page' => $page,
            'entries' => count($rows),
            'total_entries' => $total,
        ],
        'data' => $rows,
    ];

    return json_encode($response);
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

    mysqli_close($conn);

    return json_encode(array(
        "error" => 0,
        "msg" => "Job added",
    ));
}

session_start();

if (!isset($_SESSION["username"]) || $_SESSION["username"] == "") {
    unauthorized_access(UNAUTHORIZED_ACCESS, "Unauthorized access");
}

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

        echo get_jobs($count, $page);
        break;

    case "POST":
        $title = filter_input(INPUT_POST, "title");
        $description = filter_input(INPUT_POST, "description");
        $company = filter_input(INPUT_POST, "company");

        echo add_job($title, $description, $company);
        break;
    default:
        die('{"error":"Unsopported method"}');
}
?>
