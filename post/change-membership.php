<?php

if ($_POST) {
    $name = strip_tags(trim(@$_POST["name"]));

    change_membership($mysqli, $user["userId"], $name);
}

?>