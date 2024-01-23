<?php
function db_connect() {
    $db_conf_file = 'db.ini';
    if(!file_exists($db_conf_file)) {
        error_log("DB configuration not found");
        return null;
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
        error_log('Unable to connect to database: ' . mysqli_error());
        return null;
    }

    return $conn;
}
?>
