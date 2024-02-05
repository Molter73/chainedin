<?php
function download_profile_pic($id, $pic) {
    if (!array_key_exists($pic, $_FILES) || $_FILES[$pic]['size'] == 0) {
        return null;
    }

    $allowed_formats = array("jpg", "jpeg", "png");
    $filename = $_FILES[$pic]["name"];

    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!in_array($ext, $allowed_formats)) {
        return null;
    }

    $output_dir = "../imgs/profiles/$id";

    // Create directory if it doesn't exist
    if (!is_dir($output_dir)) {
        !mkdir($output_dir, 0777, true);
    }
    $output_path = "$output_dir/pic.$ext";

    if (!move_uploaded_file($_FILES["pic"]["tmp_name"], $output_path)) {
        return null;
    }

    return $output_path;
}
?>
