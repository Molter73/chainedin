<?php
/*
 * Cierra la sesión de un usuario. Elimina la cookie utilizada por el frontend.
 */
session_start();
//destroy the session
session_unset();

echo json_encode(array(
    "error" => 0,
    "msg" => "Logged out",
));
?>
