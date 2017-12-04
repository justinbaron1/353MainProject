<?php
    include_once("common/user.php");
    include_once("utils/validation.php");

    $buyerseller_infos = get_buyerseller_info($mysqli, $user["userId"]);
    if(empty($buyerseller_infos)) {
        redirect_index();
        return;
    }

    if($_GET){

        $date = sanitize(@$_GET["date"]);
        $startTime = sanitize(@$_GET["startTime"]);
        $endTime = sanitize(@$_GET["endTime"]);
        $storeId = sanitize(@$_GET["storeId"]);
        $includesDelivery = sanitize(@$_GET["includesDelivery"]) === "true" ? 1 : 0;

        $start_time = date('H:i', mktime($startTime, 0));
        $end_time = date('H:i', mktime($endTime, 0));

        echo compute_price($mysqli, $date, $start_time, $end_time, $storeId, $includesDelivery);
    }

?>