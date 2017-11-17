<?php

    if(!session_id()) session_start();
    switch($_SESSION['UserLevel']) {
        case "admin":
            header("Location: admin_index.php");
            break;
        case "visitor":
            header("Location: visitor_index.php");
            break;
    }

?>