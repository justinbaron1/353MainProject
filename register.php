<?php

session_start();

include_once("utils/user.php");

$errors = [];

if ($_POST) {
  $first_name =            @$_POST["first_name"];
  $last_name =             @$_POST["last_name"];
  $phone =                 @$_POST["phone"];
  $email =                 @$_POST["email"];
  $password =              @$_POST["password"];
  $password_confirmation = @$_POST["password_confirmation"];
  $civic_number =          @$_POST["civic_number"];
  $street =                @$_POST["street"];
  $postal_code =           @$_POST["postal_code"];
  $city =                  @$_POST["city"];

  // TODO(tomleb): Actually validate
  if (!$first_name) { $errors["first_name"] = "Invalid first name."; }
  if (!$last_name) { $errors["last_name"] = "Invalid last name."; }
  if (!$phone) { $errors["phone"] = "Invalid phone."; }
  if (!$email) { $errors["email"] = "Invalid email."; }
  if (!$password) { $errors["password"] = "Invalid password."; }
  if (!$password_confirmation) { $errors["password_confirmation"] = "??"; }
  if (!$civic_number) { $errors["civic_number"] = "Invalid civic number."; }
  if (!$street) { $errors["street"] = "Invalid street."; }
  if (!$postal_code) { $errors["postal_code"] = "Invalid postal code."; }
  if (!$city) { $errors["city"] = "Invalid city."; }

  if (empty($errors)) {
    // TODO(tomleb): Make transaction..
    $mysqli = get_database();
    $address_id = create_address($mysqli, $civic_number, $street, $postal_code, $city);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $user_id = create_user($mysqli, $first_name, $last_name, $phone, $email, $hashed_password, $address_id);
    $user = get_user_by_id($mysqli, $user_id);
    if ($user) {
      $_SESSION["user"] = $user;
    }
    header("Location: index.php");
    return;
  }

}

function form_group($errors, $label) {
  if ($errors[$label]) {
    echo "<div class=\"form-group has-error\"><label class=\"control-label\" for=\"${labels}\"> ${errors[$label]}</label>";
  } else {
    echo '<div class="form-group">';
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
                            <input id="city" placeholder="City" type="text" class="form-control"  name="city">
                        </div>
                        <button type="submit" class="btn btn-default">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
