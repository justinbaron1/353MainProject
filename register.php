<?php

session_start();

include_once("utils/user.php");
include_once("utils/validation.php");

$errors = [];

$mysqli = get_database();

if ($_POST) {
  $first_name =            strip_tags(trim(@$_POST["first_name"]));
  $last_name =             strip_tags(trim(@$_POST["last_name"]));
  $phone =                 strip_tags(trim(@$_POST["phone"]));
  $email =                 strip_tags(trim(@$_POST["email"]));
  $password =              strip_tags(trim(@$_POST["password"]));
  $password_confirmation = strip_tags(trim(@$_POST["password_confirmation"]));
  $civic_number =          strip_tags(trim(@$_POST["civic_number"]));
  $street =                strip_tags(trim(@$_POST["street"]));
  $postal_code =           strip_tags(trim(@$_POST["postal_code"]));
  $city =                  strip_tags(trim(@$_POST["city"]));

  // TODO(tomleb): Actually validate
  if (empty($first_name)) { $errors["first_name"] = "Invalid first name."; }
  if (empty($last_name))  { $errors["last_name"] = "Invalid last name."; }
  if (empty($phone)) { $errors["phone"] = "Invalid phone."; }
  if (!is_valid_email($email)) { $errors["email"] = "Invalid email."; }
  if (!is_valid_password($password)) {
    $errors["password"] = "Invalid password. Must be 8 characters or more.";
  } else if ($password !== $password_confirmation) { 
    $errors["password"] = "The two passwords are different.";
  }
  if (!is_valid_number($civic_number)) { $errors["civic_number"] = "Invalid civic number."; }
  if (empty($street))                  { $errors["street"] = "Invalid street."; }
  if (!is_valid_number($postal_code))   { $errors["postal_code"] = "Invalid postal code."; }
  if (!is_valid_city($mysqli, $city))  { $errors["city"] = "Invalid city."; }

  if (empty($errors)) {
    // TODO(tomleb): Make transaction..
    $address_id = create_address($mysqli, $civic_number, $street, $postal_code, $city);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $user_id = create_user($mysqli, $first_name, $last_name, $phone, $email, $hashed_password, $address_id);
    $user = get_user_by_id($mysqli, $user_id);
    if ($user) {
      $_SESSION["user"] = $user;
    }
    redirect_index();
    return;
  }

}

$cities = get_all_city($mysqli);

function form_group($errors, $label) {
  if (isset($errors[$label])) {
    echo "<div class=\"form-group has-error\"><label class=\"control-label\" for=\"${label}\"> ${errors[$label]}</label>";
  } else {
    echo '<div class="form-group">';
  }
}

?>

<!-- Make this form sticky ? -->
<html>
    <head>
        <?php include_once("common/head.php") ?>
    </head>
    <body>
        <?php include("common/navbar.php") ?>
        <div class="container background">
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    <h1 class="text-center white-text">Register</h1>
                    <form method="post">
                        <?php form_group($errors, "first_name");  ?>
                            <input id="first_name" placeholder="First name" type="text" class="form-control"  name="first_name">
                        </div>
                        <?php form_group($errors, "last_name");  ?>
                            <input id="last_name" placeholder="Last name" type="text" class="form-control"  name="last_name">
                        </div>
                        <?php form_group($errors, "password");  ?>
                            <input id="password" placeholder="Password" type="text" class="form-control"  name="password">
                        </div>
                        <?php form_group($errors, "password_confirmation");  ?>
                            <input id="password_confirmation" placeholder="Confirm" type="text" class="form-control"  name="password_confirmation">
                        </div>
                        <?php form_group($errors, "phone");  ?>
                            <input id="phone" placeholder="Phone" type="text" class="form-control" name="phone">
                        </div>
                        <?php form_group($errors, "email");  ?>
                            <input id="email" placeholder="Email" type="text" class="form-control"  name="email">
                        </div>
                        <?php form_group($errors, "civic_number");  ?>
                            <input id="civic_number" placeholder="Civic number" type="text" class="form-control"  name="civic_number">
                        </div>
                        <?php form_group($errors, "street");  ?>
                            <input id="street" placeholder="Street" type="text" class="form-control"  name="street">
                        </div>
                        <?php form_group($errors, "postal_code");  ?>
                            <input id="postal_code" placeholder="Postal code" type="text" class="form-control"  name="postal_code">
                        </div>
                        <div class="form-group">
                            <select class="form-control">
                                <?php foreach ($cities as $city) { ?>
                                <option value="<?= $city["city"] ?>"><?= $city["city"] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-default">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
