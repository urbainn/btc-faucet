<?php

include_once('../../src/config/pdo.php');
include_once('../../src/components/functions.php');

$userInfos = getUserInfos($pdo);
if($userInfos["diceCashbackReward"] < 1) {
    die;
}

$pdo->query("UPDATE info_users SET sats_balance = sats_balance + diceCashbackReward, multiplierSatsEarned = multiplierSatsEarned + diceCashbackReward, diceCashbackReward = 0 WHERE id = ".$userInfos["id"]);
echo floor($userInfos["diceCashbackReward"]);
?>