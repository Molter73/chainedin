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

function get_jobs($count, $page) {
    $offset = $page * $count;

    $conn = db_connect();

    $stmt = mysqli_prepare($conn, "SELECT * FROM jobs ORDER BY creation_date DESC LIMIT ?,?");
    mysqli_stmt_bind_param($stmt, "ii", $offset, $count);
    if (!mysqli_stmt_execute($stmt)) {
        die("Failed to get posts");
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

    default:
        die('{"error":"Unsopported method"}');
}
?>
