<?php
include_once('../../src/config/pdo.php');
include_once('../../src/components/functions.php');

$userInfos = getUserInfos($pdo);
if(intval($userInfos["nextLevelXP"]) - intval($userInfos["userLevelXP"]) > 0) {
    die();
}

$newLevelObjective = intval($userInfos["nextLevelXP"])+(((intval($userInfos["nextLevelXP"])/100)*(10-(($userInfos["userLevel"]/($userInfos["userLevel"] < 200 ? 48 : 200)))))+($userInfos["userLevel"] <= 30 ? 50 : 250));
$pdo->query("UPDATE info_users SET nextLevelXP = '$newLevelObjective', gainMultiplier = ".($userInfos["gainMultiplier"]+0.001).", diceCashback = ".($userInfos["diceCashback"]+0.01).", faucetTimer = faucetTimer - 1, userLevel = userLevel+1 WHERE id = ".$userInfos["id"]);

$userInfos = getUserInfos($pdo);
?>

<h3 class="t-white">Level <?php echo $userInfos["userLevel"]; ?></h3>
<h5 class="t-grey">Only <span
        class="t-orange"><?php echo reduceNumber(intval($userInfos["nextLevelXP"]) - intval($userInfos["userLevelXP"]), 99999); ?>XP</span>
    left before
    lvl <?php echo intval($userInfos["userLevel"])+1; ?></h5>
<div class="progress mt-2">
    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated w-100-p" role="progressbar"
        aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
        style="width: <?php echo (intval($userInfos["userLevelXP"]) / intval($userInfos["nextLevelXP"])*100); ?>%">
    </div>
</div>