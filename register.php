<?php

session_start();

include_once("utils/user.php");
include_once("utils/validation.php");

if (isset($_SESSION["user"])) {
  redirect_index();
  return;
}

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

  $errors = validate_registration($first_name, $last_name, $phone, $email, $password, 
                                  $password_confirmation, $civic_number, $street, $postal_code, $city);

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

function form_group($errors, $name, $label = null) {
  if (isset($errors[$name])) {
    echo "<div class=\"form-group has-error\"><label class=\"control-label\" for=\"${name}\"> ${errors[$name]} </label>";
  } else if ($label) {
    echo "<div class=\"form-group\"><label class=\"control-label\" for=\"${name}\"> $label </label>";
  } else {
    echo '<div class="form-group">';
  }
}

function select_if_equal($a, $b) {
  if ($a === $b) {
    echo 'selected';
  }
}

?>

<html>
    <head>
        <?php include_once("common/head.php") ?>
    </head>
    <body>
        <?php include("common/navbar.php") ?>
        <div class="container background">
            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                    <h1 class="text-center white-text">Register</h1>
                    <form method="post">
                      <div class="row">
                        <div class="col-md-6">
                          <?php form_group($errors, "first_name", "First name");  ?>
                            <input id="first_name" placeholder="First name" type="text" class="form-control"  name="first_name" value="<?= $first_name ?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <?php form_group($errors, "last_name", "Last name");  ?>
                              <input id="last_name" placeholder="Last name" type="text" class="form-control"  name="last_name" value="<?= $last_name ?>">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <?php form_group($errors, "password", "Password");  ?>
                              <input id="password" placeholder="Password" type="password" class="form-control"  name="password">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <?php form_group($errors, "password_confirmation", "Confirmation");  ?>
                              <input id="password_confirmation" placeholder="Confirm" type="password" class="form-control"  name="password_confirmation">
                          </div>
                        </div>
                      </div>
                        <?php form_group($errors, "phone", "Phone");  ?>
                            <input id="phone" placeholder="(XXX) XXX-XXXX" type="text" class="form-control" name="phone" value="<?= $phone ?>">
                        </div>
                        <?php form_group($errors, "email", "Email");  ?>
                            <input id="email" placeholder="john.smith@example.com" type="text" class="form-control"  name="email" value="<?= $email ?>">
                        </div>

                      <div class="row">
                        <div class="col-md-3">
                          <?php form_group($errors, "civic_number", "Civic");  ?>
                              <input id="civic_number" placeholder="1000" type="text" class="form-control"  name="civic_number" value="<?= $civic_number ?>">
                          </div>
                        </div>
                        <div class="col-md-9">
                          <?php form_group($errors, "street", "Street");  ?>
                              <input id="street" placeholder="DeMaisoneuve St." type="text" class="form-control"  name="street" value="<?= $street ?>">
                          </div>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-6">
                          <?php form_group($errors, "postal_code", "Postal code");  ?>
                              <input id="postal_code" placeholder="AXA XAX" type="text" class="form-control"  name="postal_code" value="<?= $postal_code ?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <?php form_group($errors, "city", "City"); ?>
                              <select class="form-control" name="city">
                                  <?php foreach ($cities as $c) { ?>
                                    <option value="<?= $c["city"] ?>" <?= select_if_equal($city, $c["city"]) ?>><?= $c["city"] ?></option>
                                  <?php } ?>
                              </select>
                          </div>
                        </div>
                        <!-- TODO Align this stupid shiet -->
                        <div class="row form-group">
                          <button type="submit" class="btn btn-default">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
