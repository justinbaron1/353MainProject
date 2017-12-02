<?php

// inb4 someone uploads a file that starts with http
function is_http_link($string) {
  return substr($string, 0, 4) === "http";
}

function is_file_to_upload($file) {
  return $file["error"] === UPLOAD_ERR_OK;
}

function real_upload_path($upload_dir, $file) {
  return getcwd() . "/" . $upload_dir . "/" . $file;
}

// INFO: We're putting the files in a flat way which can cause naming conflicts
// TODO(tomleb): Handle name conflict, I don't even think it's necessary for the
// project so I'm skipping this for now.
function handle_ad_image_upload($mysqli, $file, $old_file = '') {
  if ($file['errors'] == UPLOAD_ERR_OK) {
    $ini = parse_ini_file("sikrits.env");
    $upload_dir = $ini["UPLOAD"];

    if ($old_file && $old_file !== '') {
      if (is_http_link($old_file)) {
        log_info("Old file '$old_file' is a link. Not removing anything from server.");
      } else {
        log_info("Removing old image '$old_file'");
        unlink(real_upload_path($upload_dir, $old_file));
      }
    }

    $destination_file = real_upload_path($upload_dir, $file['name']);

    // TODO(tomleb): Surround with try catch to prevent warning ?
    $result = move_uploaded_file($file['tmp_name'], $destination_file);

    if ($result) {
      log_info("Moved image '${file['tmp_name']}' to '$destination_file'");
    } else {
      log_info("Problem moving image '${file['tmp_name']}' to '$destination_file'");
    }

    return $result;
  }

  return false;
}

?>
