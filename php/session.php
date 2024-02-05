<?php
function validate_session() {
    session_start();

    if (!isset($_SESSION["user_id"]) || $_SESSION["user_id"] == "") {
        unauthorized_access(UNAUTHORIZED_ACCESS, "Unauthorized access");
    }
}

?>
