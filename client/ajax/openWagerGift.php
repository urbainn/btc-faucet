<?php

include_once('../../src/config/pdo.php');
include_once('../../src/components/functions.php');

$userInfos = getUserInfos($pdo);
if($userInfos["wageredSinceGift"] < 200) {
    die();
}

$giftType = rand(1, 2);
if($giftType == 1) {
    // XP
    $xpAmount = rand(5, 25);
    $pdo->query("UPDATE info_users SET wageredSinceGift = 0, userLevelXP = userLevelXP + ".$xpAmount." WHERE id = ".$userInfos["id"]);
    echo "You just earned <span class='t-blue'>$xpAmount XP</span>";
} elseif($giftType == 2) {
    // Lower facet timer
    $reduceAmount = rand(2, 7);
    $pdo->query("UPDATE info_users SET wageredSinceGift = 0, lastClaimTimestamp = lastClaimTimestamp - ".($reduceAmount*60)." WHERE id = ".$userInfos["id"]);
    echo "Your faucet timer has been reduced by <span class='t-blue'>$reduceAmount minutes</span> !";
}

?>