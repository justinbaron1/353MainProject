<?php

// inb4 someone uploads a file that starts with http
function is_http_link($string) {
  return substr($string, 0, 4) === "http";
}

function is_file_to_upload($file) {
  return $file["error"] === UPLOAD_ERR_OK;
}

function image_to_link($image) {
  if (is_http_link($image)) {
    return $image;
  } else {
    $ini = parse_ini_file("sikrits.env");
    $upload_dir = $ini["UPLOAD"];
    return $upload_dir . "/" . $image;
  }
}

function real_upload_path($upload_dir, $file) {
  return getcwd() . "/" . $upload_dir . "/" . $file;
}

// INFO: We're putting the files in a flat way which can cause naming conflicts
function handle_ad_image_upload($mysqli, $file, $old_file = '') {
  if ($file['error'] == UPLOAD_ERR_OK) {
    $ini = parse_ini_file("sikrits.env");
    $upload_dir = $ini["UPLOAD"];

    if (!file_exists($upload_dir) && !mkdir($upload_dir, 0770)) {
      log_info("Unable to create upload directory '$upload_dir'");
      return false;
    }

    if ($old_file && $old_file !== '') {
      if (is_http_link($old_file)) {
        log_info("Old file '$old_file' is a link. Not removing anything from server.");
      } else {
        log_info("Removing old image '$old_file'");
        unlink(real_upload_path($upload_dir, $old_file));
      }
    }

    $destination_file = real_upload_path($upload_dir, $file['name']);

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
