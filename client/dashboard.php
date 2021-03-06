<?php
    $originPath = "client/dashboard";
    include_once('../src/components/header.php');

    if($userInfos["userLevelXP"] - $userInfos["nextLevelXP"] > 230) {
        echo "<script>updateLevel();</script>";
    }
?>

<link rel="stylesheet" href="../src/css/client/contact.css">

<div class="responsive-container flex center wrap">
    <div class="col-lg-9">
        <h1 class="mt-5">Hello, <?php echo htmlspecialchars($userInfos["username"]); ?></h1>
    </div>
    <div class="col-lg-9 flex gap-1em mobile-wrap mobile-no-gap">
        <div class="illustr-box bg-dark col-lg-6 col-xs-12">
            <div class="illustr">
                <i class="fa fa-wallet"></i>
            </div>
            <div>
                <h3 class="t-white">Balance</h3>
                <h5 class="t-grey">You have <span class="t-orange"><?php echo $userInfos["sats_balance"] ?></span>
                    satoshis (<span
                        class="t-orange"><?php echo htmlspecialchars(file_get_contents("http://codacoin.com/api/public.php?request=convert&type=btctofiat&input=".convertToBTCFromSatoshi($userInfos["sats_balance"])."&symbol=enabled&decimal=4&exchange=average&currency=USD&denom=bitcoin")); ?></span>
                    )
                </h5>
            </div>
        </div>
        <div class="illustr-box bg-dark col-lg-6 col-xs-12">
            <div class="illustr">
                <i class="fa fa-magic"></i>
            </div>
            <div class="w-100-p" id="userLevelMessage">
                <h3 class="t-white">Level <?php echo $userInfos["userLevel"]; ?></h3>
                <?php if(intval(($userInfos["nextLevelXP"]) - intval($userInfos["userLevelXP"])) > 0) { ?>
                <h5 class="t-grey">Only <span
                        class="t-orange"><?php echo reduceNumber(intval($userInfos["nextLevelXP"]) - intval($userInfos["userLevelXP"]), 99999); ?>XP</span>
                    left before
                    lvl <?php echo intval($userInfos["userLevel"])+1; ?></h5>
                <div class="progress mt-2">
                    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated w-100-p"
                        role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                        style="width: <?php echo (intval($userInfos["userLevelXP"]) / intval($userInfos["nextLevelXP"])*100); ?>%">
                    </div>
                </div>
                <?php } else { ?>
                <h5 class="t-grey">You have enough XP to level up !</h5>
                <div class="flex mt-2">
                    <div class="button" onclick="updateLevel()">Upgrade</div>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php 
        $userMessages = $pdo->query("SELECT * FROM contact_topics WHERE authorID = ".$userInfos["id"]." AND userRead = 0 ORDER BY creationTimestamp DESC")->fetchAll();
        if(count($userMessages) > 0) { ?>
        <h1>Unread Messages</h1>
        <div class="message-list mb-5">
            <div class="list-header">
                <h3>Support Answered!</h3>
            </div>
            <?php foreach($userMessages as $msg){ ?>
            <a class="list-element" href="thread.php?topicID=<?php echo $msg['id']; ?>">
                <div>
                    <h5><?php echo htmlspecialchars($msg["topicTitle"]);
                    if($msg["closed"] == 0) echo ' <span class="badge badge-success">Opened</span>';
                    else echo ' <span class="badge badge-danger">Closed</span>';
                    if($msg["userRead"] == 0) echo ' <span class="badge badge-warning">Unread</span>'; ?></h5>
                </div>
            </a>
            <?php } ?>
        </div>
        <?php } ?>

    </div>
    <div class="col-lg-9 col-xs-12 mt-5">
        <h1 class="mb-1">Active bonuses</h1>
        <div class="display-box bg-dark w-100p flex m-0 gap-1em mobile-wrap">
            <div class="col-lg-3 col-xs-12">
                <div class="multiplier">
                    <h1>x<?php echo number_format((float)$userInfos["gainMultiplier"], 3, '.', ''); ?></h1>
                </div>
            </div>
            <div>
                <h4 class="t-white bonuses-list">
                    <span class="bonus-name">Multiplier:</span>
                    x<?php echo number_format((float)$userInfos["gainMultiplier"], 3, '.', ''); ?> <span
                        class="t-grey ml-2 h5">Next level: <span
                            class="bonus-next">x<?php echo number_format((float)$userInfos["gainMultiplier"], 3, '.', '')+0.001; ?></span></span>

                    <br><span class="bonus-name">Timer:</span>
                    <?php echo ($userInfos["faucetTimer"]); ?>s <span class="t-grey ml-2 h5">Next level: <span
                            class="bonus-next"><?php echo ($userInfos["faucetTimer"]-1); ?>s</span></span>

                    <br><span class="bonus-name">Dice Cashback:</span>
                    <?php echo number_format((float)$userInfos["diceCashback"], 2, '.', ''); ?>
                    % <span class="t-grey ml-2 h5">Next level: <span
                            class="bonus-next"><?php echo number_format((float)$userInfos["diceCashback"], 2, '.', '')+0.01; ?>
                            %</span></span>

                    <br><span class="bonus-name">You earned: </span>
                    <?php echo round($userInfos["multiplierSatsEarned"]); ?> sats<span class="t-grey ml-2 h5">thanks
                        to
                        bonuses</span>
                </h4>
            </div>
        </div>
        <div class="d-sm-n flex center mt-3 w-100-p">
            <?php displayAdvert("rectangle", 5, $userInfos["userLevel"]); ?>
            <?php displayAdvert("rectangle", 5, $userInfos["userLevel"]); ?>
        </div>
    </div>
    <div class="col-lg-9 col-xs-12 mt-4">
        <h1 class="mb-1">Earn satoshis</h1>
        <div class="flex wrap w-100-p gap-1em align-center">
            <div class="round-button">
                <i class="fa fa-faucet"></i>
            </div>
            <div class="round-button">
                <i class="fa fa-dice"></i>
            </div>
            <h4 class="t-grey">More coming soon...</h4>
        </div>
    </div>
</div>

<script>
function updateLevel() {
    $.ajax({
        type: "POST",
        url: "ajax/updateUserLevel.php",
        success: (returnValue) => {
            let levelMessageElement = document.getElementById('userLevelMessage');
            levelMessageElement.innerHTML = returnValue;
        },
        dataType: "HTML"
    });
}
</script>

<?php
include_once('../src/components/footer.php');
?>