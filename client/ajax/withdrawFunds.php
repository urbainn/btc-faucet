<?php
include_once('../../src/config/pdo.php');
include_once('../../src/components/functions.php');
include_once('../../src/components/faucethub.php');

$userInfos = getUserInfos($pdo);
if(!$userInfos) { die(); }
$withdrawAmount = ($userInfos["sats_balance"] > 9000 ? 9000 : $userInfos["sats_balance"]);

// Options
$minimalWithdrawAmount = 5000;

if(time() - $userInfos["floodFilter_LastHit"] < 5) {
    echo "Cool down! Please wait 5 seconds between each try.";
    die();
}

// Update flood filter last hit
$pdo->query("UPDATE info_users SET floodFilter_LastHit = '".time()."' WHERE id = ".$userInfos["id"]);

if($withdrawAmount < $minimalWithdrawAmount) {
    echo "Sorry, but currently, the minimal withdraw amount is $minimalWithdrawAmount satoshis... It will be lowered soon, keep on earning!";
    die();
}

if($userInfos["userLevel"] < 40) {
    echo "It seems your account is not ready for a withdraw yet, earn some XP and come back at level 40!";
    die();
}

$transferInfos = sendCoins($userInfos["linkedBtcAddress"], $withdrawAmount, "BTC");
if($transferInfos->status == 200) {

    $projectRemainingFunds = $transferInfos->balance;
    $pdo->query("UPDATE info_users SET sats_balance = sats_balance - $withdrawAmount WHERE id = ".$userInfos["id"].";");
    $pdo->query("UPDATE info_globalStats SET statValue = statValue + $withdrawAmount WHERE statName = 'totalWithdraw';");
    $pdo->query("INSERT INTO `logs_withdraws` (`userid`, `withdrawAmount`, `withdrawTimestamp`) VALUES ('".$userInfos["id"]."', '$withdrawAmount', '".time()."');");

} else {
    if($transferInfos->status == 402) echo "Whoops... it looks like too much people transfered their funds today.. Please come back later, we reinvest funds every day!";
    if($transferInfos->status == 405) echo "To prevent frauds, you cannot withdraw more than 9k satoshis every hour, please come back later...";
    if($transferInfos->status == 456) echo "It seems that the BTC address you linked to your account is not correct anymore. Plase, update it.";
    if($transferInfos->status == 457) echo "We are sorry but you have been blacklisted from doing withdraws. Please contact support.";

    die();
}
?>