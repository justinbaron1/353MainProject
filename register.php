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
                        <?php form_group($errors, "city");  ?>
                            <select class="form-control" name="city">
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
