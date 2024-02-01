<?php
/**
 * Permite interactuar con la tabla de aplicantes a una posición basado en
 * el método HTTP utilizado.
 *
 * POST: Permite a un candidato presentarse para una posición. Parámetros:
 * - user_id
 * - job_id
 *
 * GET: Devuleve listas de trabajos-aplicantes. Parámetros:
 * - user_id: filtra resultados para incluir sólo trabajos de este usuario.
 * - job_id: filtra resultados para incluir sólo postulantes de este trabajo.
 * - count: cantidad de resultados a devolver (default: 20)
 * - page: Página de resultados a obtener (default:0)
 */
include_once("error.php");
include_once("db.php");
include_once("session.php");

function apply($user_id, $job_id) {
    if (is_null($job_id) || $job_id === false) {
        bad_request(INVALID_ARGUMENT, "Invalid job id");
    }

    $conn = db_connect();

    $stmt = mysqli_prepare($conn, "INSERT INTO applicants(user_id, job_id) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $job_id);
    if (!mysqli_stmt_execute($stmt)) {
        internal_error(
            DATABASE_QUERY_ERROR,
            "Failed to create application - " . mysqli_error($conn)
        );
    }

    return json_encode(array(
        "error" => 0,
        "msg" => "Application created successfully",
    ));
}

function get_user_applications_stmt($conn, $user_id, $count, $offset){
    $stmt = mysqli_prepare(
        $conn,
        "SELECT title, description, company FROM jobs INNER JOIN applicants ON jobs.id = applicants.job_id WHERE applicants.user_id = ? LIMIT ?, ?"
    );
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $offset, $count);

    return $stmt;
}

function get_job_applications_stmt($conn, $job_id, $count, $offset){
    $stmt = mysqli_prepare(
        $conn,
        "SELECT name, email FROM users INNER JOIN applicants ON users.id = applicants.user_id WHERE applicants.job_id = ? LIMIT ?, ?"
    );
    mysqli_stmt_bind_param($stmt, "iii", $job_id, $offset, $count);

    return $stmt;
}

function get_applications($user_id, $job_id, $count, $page) {
    if (is_null($user_id) || $user_id === false) {
        bad_request(INVALID_ARGUMENT, "Invalid user id");
    }

    if ($job_id === false) {
        bad_request(INVALID_ARGUMENT, "Invalid user id");
    }

    $conn = db_connect();

    $offset = $page * $count;
    $stmt = is_null($job_id) ?
                get_user_applications_stmt($conn, $user_id, $count, $offset) :
                get_job_applications_stmt($conn, $job_id, $count, $offset);

    if (!mysqli_stmt_execute($stmt)) {
        internal_error(DATABASE_QUERY_ERROR, mysqli_error());
    }

    $res = mysqli_stmt_get_result($stmt);
    $rows = mysqli_fetch_all($res, MYSQLI_ASSOC);

    return json_encode(array(
        "error" => 0,
        "data" => $rows,
    ));
}

validate_session();

switch ($_SERVER["REQUEST_METHOD"]) {
    case "POST":
        $job_id = filter_input(INPUT_POST, "job_id", FILTER_VALIDATE_INT);
        echo apply($_SESSION["user_id"], $job_id);
        break;

    case "GET":
        $user_id = filter_input(INPUT_GET, "user_id", FILTER_VALIDATE_INT, [
            "options" => [
                "default" => $_SESSION["user_id"],
            ]
        ]);
        $job_id = filter_input(INPUT_GET, "job_id", FILTER_VALIDATE_INT);
        $count = filter_input(INPUT_GET, "count", FILTER_VALIDATE_INT, [
            "options" => [
                "default" => 20
        ]]);
        $page = filter_input(INPUT_GET, "page", FILTER_VALIDATE_INT, [
            "options" => [
                "default" => 0
        ]]);

        echo get_applications($user_id, $job_id, $count, $page);
        break;

    default:
        unsupported_method();
}
?>
