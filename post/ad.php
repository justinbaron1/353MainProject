<?php

include_once("utils/database.php");
include_once("utils/upload.php");
include_once("utils/validation.php");

function handle_create_ad(&$ad_id, $user_id, $title, $price, $description,
                          $category, $sub_category, $type, $file, $promotion_package) {
  $mysqli = get_database();
  if (empty(get_buyerseller_info($mysqli, $user_id))) {
    log_info("User '$user_id' is not a BuyerSeller. Cannot create ad.");
    // This is very useless right now.
    return array(
      'create_ad' => "Cannot create ad",
    );
  }

  $errors = validate_ad($title, $price, $description,
                        $category, $sub_category, $type, $file);

  if (!empty($errors)) {
    return $errors;
  }

  if(!empty(@$file['name'])){
    $file["name"] = name_with_GUID($file);
  }
  $ad_id = create_ad_with_image($mysqli, $user_id, $title, $price, $description, $type, $category, $sub_category, @$file['name']);
  if ($ad_id) {
    log_info("Created new ad '$ad_id' by user '$user_id'");
  } else {
    log_info("Failed creating new ad by user '$user_id'");
    $errors['general'] = "Failed creating new ad by user '$user_id': " . $mysqli->error;
    return $errors;
  }

  if ($promotion_package > 0) {
    $error = create_and_link_promotion_package($mysqli, $promotion_package, $ad_id);
    if ($error) {
      log_info("Failed creating ad promotion package for ad '$ad_id' and duration '$promotion_package'");
    } else {
      log_info("Created ad promotion package for ad '$ad_id' and duration '$promotion_package'");
    }
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
                          $category, $sub_category, $type, $image_file, $promotion_package) {
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

  if ($promotion_package > 0 && !promotion_exists($mysqli, $ad_id)) {
    $error = create_and_link_promotion_package($mysqli, $promotion_package, $ad_id);
    if ($error) {
      log_info("Failed creating ad promotion package for ad '$ad_id' and duration '$promotion_package'");
    } else {
      log_info("Created ad promotion package for ad '$ad_id' and duration '$promotion_package'");
    }
  }

  if (is_file_to_upload($image_file)) {
    $image_file["name"] = name_with_GUID($image_file);
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

function name_with_GUID($image_file){
  $path_parts = pathinfo($image_file["name"] );
  return trim(com_create_guid(), '{}').".".$path_parts["extension"];
}

function handle_delete_ad($user_id, $ad_id) {
  global $mysqli;
  $mysqli = get_database();
  $errors = [];

  if (can_edit_ad($mysqli, $ad_id, $user_id)) {
    if (!delete_ad($mysqli, $ad_id)) {
      $errors['delete'] = "Problem deleting ad '$ad_id'";
      log_info("Problem deleting ad '$ad_id'");
    }
  } else {
    log_info("Non-authorized user '$user_id' tried to delete ad '$ad_id'. Aborting.");
    $errors['delete'] = "Non-authorized user '$user_id' tried to delete ad '$ad_id'. Aborting.";
  }
  return $errors;
}

?>
