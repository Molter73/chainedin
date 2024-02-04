<?php
define("INVALID_ARGUMENT", 1);
define("LOGIN_FAILED", 2);
define("UNSUPPORTED_METHOD", 3);
define("UNAUTHORIZED_ACCESS", 4);

define("DATABASE_CONNECTION_ERROR", 100);
define("DATABASE_CONFIGURATION_ERROR", 101);
define("DATABASE_QUERY_ERROR", 102);

function die_json($err_code, $msg) {
    die(json_encode(array(
        "error" => $err_code,
        "msg" => $msg,
    )));
}

function internal_error($err_code, $msg) {
    http_response_code(500);
    die_json($err_code, $msg);
}

function bad_request($err_code, $msg) {
    http_response_code(400);
    die_json($err_code, $msg);
}

function unauthorized_access($err_code, $msg) {
    http_response_code(403);
    die_json($err_code, $msg);
}

function unsupported_method() {
    http_response_code(405);
    die_json(UNSUPPORTED_METHOD, "Unsupported method");
}
?>
