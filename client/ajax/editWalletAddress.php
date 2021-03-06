<?php
include_once('../../src/config/pdo.php');
include_once('../../src/components/functions.php');
include_once('../../src/components/faucethub.php');

$userInfos = getUserInfos($pdo);
$newWalletAddress = htmlspecialchars($_POST["walletAddress"]);

if(time() - $userInfos["floodFilter_LastHit"] < 5) {
    echo "Cool down! Please wait 5 seconds between each try.";
} else {
    if(preg_match("/^[13][a-km-zA-HJ-NP-Z1-9]{25,34}$/", $newWalletAddress)) {
        if(checkAddress("BTC", $newWalletAddress)->status == "200") {
            $pdo->query("UPDATE info_users SET floodFilter_LastHit = '".time()."', linkedBtcAddress = \"$newWalletAddress\" WHERE id = ".$userInfos["id"]);
        } else {
            // address doesn't exist'
            echo "This address isn't linked to any FaucetPay account. Go to <a href='https://faucetpay.io/?r=144281'>FaucetPay.io</a> > Dashboard > Linked Addresses";
            $pdo->query("UPDATE info_users SET floodFilter_LastHit = '".time()."' WHERE id = ".$userInfos["id"]);
        };
    } else {
        // entered address doesn't suit a valid btc address representation
        echo "The BTC address you've entered doesn't look like a real address. Please check that it really is a BTC address and not an other cryptocurrency.";
        $pdo->query("UPDATE info_users SET floodFilter_LastHit = '".time()."' WHERE id = ".$userInfos["id"]);

    }
}
?>