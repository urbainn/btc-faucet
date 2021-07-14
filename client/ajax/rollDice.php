<?php

    include_once('../../src/config/pdo.php');
    include_once('../../src/components/functions.php');

    $userInfos = getUserInfos($pdo);

    $betAmount = intval(htmlspecialchars($_POST['betAmount']));
    $betColor = intval(htmlspecialchars($_POST['betColor']));
    $randomOutput = intval(rand(2,98));

    // 2021 patch: negative bet amount exploit patched
    if($betAmount < 1) die("NEGATIVE-BET-AMOUNT");

    $wonAmount = 0; // If negative, user lost (in satoshis)
    if($betColor == 0) {
        if($randomOutput < 50) $wonAmount = $betAmount;
        if($randomOutput >= 50) $wonAmount = 0 - $betAmount;
    } else {
        if($randomOutput > 50) $wonAmount = $betAmount;
        if($randomOutput <= 50) $wonAmount = 0 - $betAmount;
    }

    if($wonAmount < 0) {
        $diceCashback = (($betAmount/100) * $userInfos["diceCashback"]);
    } else {
        $diceCashback = 0;
    }

    $pdo->query("UPDATE info_users SET wageredTotal = wageredTotal + $betAmount, wageredSinceGift = wageredSinceGift + $betAmount, sats_balance = sats_balance + $wonAmount, diceCashbackReward = diceCashbackReward + $diceCashback WHERE id = ".$userInfos["id"]);
    echo $randomOutput;
?>
