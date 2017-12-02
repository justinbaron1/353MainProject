<?php

include_once("utils/database.php");
include_once("utils/upload.php");
include_once("utils/validation.php");

function handle_create_ad($user_id, $title, $price, $description, 
                          $category, $sub_category, $type, $file) {
  $errors = validate_ad($title, $price, $description, 
                        $category, $sub_category, $type, $file);

  if (!empty($errors)) {
    return $errors;
  }

  $mysqli = get_database();
  $ad_id = create_ad_with_image($mysqli, $user_id, $title, $price, $description, $type, $category, $sub_category, @$file['name']);
  if ($ad_id) {
    log_info("Created new ad '$ad_id' by user '$user_id'");
  } else {
    log_info("Failed creating new ad by user '$user_id'");
    return;
  }

  if (is_file_to_upload($file)) {
    if (!handle_ad_image_upload($mysqli, $file)) {
      $errors["imageToUpload"] = "Problem uploading image";
    }
  } else {
    log_info("No image uploaded. Nothing to do.");
  }
  return $errors;
}

function handle_update_ad($ad_id, $user_id, $title, $price, $description, 
                          $category, $sub_category, $type, $image_file) {
  $mysqli = get_database();

  if (!can_edit_ad($mysqli, $ad_id, $user_id)) {
    log_info("User '$user_id' is not allowed to modify ad '$ad_id'");
    return $errors;
  }

  $errors = validate_ad($title, $price, $description, 
                        $category, $sub_category, $type, $image_file);

  if (!empty($errors)) {
    return $errors;
  }

  $old_images = get_ad_image_by_id($mysqli, $ad_id);
  $old_image = false;
  if (!empty($old_images)) {
    $old_image = $old_images["url"];
  }

  if (is_file_to_upload($image_file)) {
    if (!handle_ad_image_upload($mysqli, $image_file, $old_image)) {
        $errors["imageToUpload"] = "Problem uploading image";
    }
  }

  $error = update_ad_with_image($mysqli, $ad_id, $user_id, $title, $price, $description, 
                                $type, $category, $sub_category, @$image_file['name'], $old_image);
  if ($error) {
    log_info("Problem updating ad '$ad_id' by user '$user_id'");
    $errors["update_error"] = "Error updating ad";
  } else {
    log_info("Updated ad '$ad_id' by user '$user_id'");
  }

  return $errors;
}

function handle_delete_ad($ad_id) {
  // TODO(tomleb): Actually delete the ad
}

?>
