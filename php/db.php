<?php
include_once("error.php");

mysqli_report(MYSQLI_REPORT_ERROR);

function db_connect() {
    $db_conf_file = 'db.ini';
    if(!file_exists($db_conf_file)) {
        internal_error(DATABASE_CONFIGURATION_ERROR, "DB configuration not found");
    }

    $db_conf = parse_ini_file($db_conf_file);

    $conn = mysqli_connect(
        $db_conf["url"],
        $db_conf["user"],
        $db_conf["password"],
        $db_conf["database"],
        $db_conf["port"],
    );

    if (!$conn) {
        internal_error(
            DATABASE_CONFIGURATION_ERROR,
            'Unable to connect to database: ' . mysqli_connect_error()
        );
    }

    return $conn;
}
?>
