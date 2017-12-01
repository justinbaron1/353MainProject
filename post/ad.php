<?php

include_once("utils/database.php");
include_once("utils/upload.php");
include_once("utils/validation.php");

function handle_create_ad($user_id, $title, $price, $description, 
                          $category, $sub_category, $type, $file) {
  $errors = validate_ad($title, $price, $description, 
                        $category, $sub_category, $type, $file);

  if (empty($errors)) {
    $mysqli = get_database();
    $success = create_ad_with_image($mysqli, $user_id, $title, $price, $description, $type, $category, $sub_category, @$file['name']);
    if ($success) {
    } else {
      // Not sure what we should do here ?
      return;
    }

    if (!upload_no_file($file)) {
      if (!handle_ad_image_upload($mysqli, $file)) {
        $errors["imageToUpload"] = "Problem uploading image";
      }
    }
  }
  return $errors;
}

function handle_update_ad($ad_id, $user_id, $title, $price, $description, 
                          $category, $sub_category, $type, $image_file) {
  // TODO(tomleb): Actually update the ad
  $errors = validate_ad($title, $price, $description, 
                        $category, $sub_category, $type, $image_file);

  if (!empty($errors)) {
    return $errors;
  }

  $mysqli = get_database();

  $old_images = get_ad_image_by_id($mysqli, $ad_id);

  if (empty($old_images)) {
    $old_image = "";
  } else {
    $old_image = $old_images["url"];
  }

  if (!upload_no_file($image_file)) {
    handle_ad_image_upload($mysqli, $image_file, $old_image);
  }

  $error = update_ad_with_image($mysqli, $ad_id, $user_id, $title, $price, $description, 
                                $type, $category, $sub_category, @$image_file['name'], $old_image);
  if ($error) {
    // Heh..
    $errors["update_error"] = "Error updating ad";
  }

  return $errors;
}

function handle_delete_ad($ad_id) {
  // TODO(tomleb): Actually delete the ad
}

?>
