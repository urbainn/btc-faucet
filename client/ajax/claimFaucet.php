<?php
include_once('../../src/config/pdo.php');
include_once('../../src/components/functions.php');

// /!\ GET RID OF THIS IF YOU WANT THE FAUCET TO WORK!
die("FAUCET-DISABLED"); // You can get rid of this in order to allow claiming again

$userInfos = getUserInfos($pdo);

$rewardSatoshis = claimSatoshis($userInfos);
if($rewardSatoshis > 300000) {
    // Max reward is 300k, if reward is more, ban the user for 2h (security).
    $pdo->query('UPDATE info_users SET bannedTimestamp = '.(time() + 7200).' WHERE id = '.$userInfos["id"]);
    die;
}

// Check captcha
$data = array(
    'secret' => "", // You h-captcha secret key here
    'response' => $_POST['h-captcha-response']
);
$verify = curl_init();
curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
curl_setopt($verify, CURLOPT_POST, true);
curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($verify);
$responseData = json_decode($response);
if(!$responseData->success) {
    echo "NO-CAPTCHA";
    die;
}

if(($userInfos["lastClaimTimestamp"]+$userInfos["faucetTimer"]) >= time()) {
    die;
}

$pdo->query('UPDATE info_users SET sats_balance = sats_balance + '.$rewardSatoshis.', userLevelXP = userLevelXP + '.(ceil($rewardSatoshis/15)+10).', multiplierSatsEarned = multiplierSatsEarned + ('.($rewardSatoshis).'*(gainMultiplier-1)), lastClaimTimestamp = '.time().' WHERE id = '.$userInfos["id"]);
$pdo->query('INSERT INTO logs_claims (claimAmount, userID, timestamp) VALUES ('.$rewardSatoshis.', '.$userInfos["id"].', '.time().')');

if($userInfos["referralID"]) {
    $referrerInfos = $pdo->query("SELECT referralIncome FROM info_users WHERE id = ".$userInfos["referralID"])->fetch();
    $referralCommission = floor($rewardSatoshis*0.10); // 10% of referral gains
    $pdo->query("UPDATE info_users SET sats_balance = sats_balance + $referralCommission, userLevelXP = userLevelXP + 1, referralIncome = referralIncome + $referralCommission WHERE id = ".$userInfos["referralID"]);
}
echo $rewardSatoshis;

// Rewards exprimed in satoshis
function claimSatoshis($userInfos) {
    $randNbr = rand(0,10000);
    if($randNbr > 9990) {
        $rewardSatoshis = rand(1, 100);
    } elseif($randNbr > 9995) {
        $rewardSatoshis = rand(30, 500);
    } elseif($randNbr > 9998) {
        $rewardSatoshis = rand(100, 1000);
    } elseif($randNbr == 10000) {
        $rewardSatoshis = rand(1000, 5000);
        if($rewardSatoshis == 1000) $rewardSatoshis = 300000; // Jackpot
    } else {
        $rewardSatoshis = rand(1, 30); // Common rewards
    }
    return $rewardSatoshis;
} 
?>