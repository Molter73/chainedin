<?php
define("USUARIO", 0);
define("RECLUTADOR", 1);

function is_user($type) {
    return $type == USUARIO;
}

function is_recruiter($type) {
    return $type == RECLUTADOR;
}

function user_type_to_string($type) {
switch ($type) {
        case USUARIO:
            return "usuario";
        case RECLUTADOR:
            return "reclutador";
        default:
            return null;
    }
}
?>
