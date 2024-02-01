<?php
function validate_session() {
    session_start();

    if (!isset($_SESSION["username"]) || $_SESSION["username"] == "") {
        unauthorized_access(UNAUTHORIZED_ACCESS, "Unauthorized access");
    }
}

?>
