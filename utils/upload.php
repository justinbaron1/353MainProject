<?php

// INFO: We're putting the files in a flat way which can cause naming conflicts
// TODO(tomleb): Handle name conflict, I don't even think it's necessary for the
// project so I'm skipping this for now.
function handle_ad_image_upload($mysqli, $file) {
  if ($file['errors'] == UPLOAD_ERR_OK) {
    $ini = parse_ini_file("sikrits.env");
    $upload_dir = $ini["UPLOAD"];
    $destination_file = getcwd() . "/" . $upload_dir . "/" . $file['name'];

    // TODO(tomleb): Surround with try catch to prevent warning ?
    $result = move_uploaded_file($file['tmp_name'], $destination_file);

    if (!$result) {
      error_log("Problem moving file '${file['tmp_name']}' to '$destination_file'");
    }

    return $result;
  }

  return false;
}

?>
