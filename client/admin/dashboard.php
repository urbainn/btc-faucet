<?php
    $originPath = "client/admin/dashboard";
    include_once('../../src/components/header.php');

    if($userInfos["rank"] < 70) {
        die("Nice try :)");
    }

?>

<link rel="stylesheet" type="text/css" href="../../src/css/client/contact.css">

<div class="responsive-container flex center wrap">
    <div class="col-lg-9">
        <h1 class="mt-5">Hello, <?php echo htmlspecialchars($userInfos["username"]); ?></h1>
    </div>
    <div class="col-lg-9">
        <div class="mobile-wrap flex gap-1em">
            <div class="illustr-box bg-dark col-lg-6 col-xs-12">
                <div class="illustr">
                    <i class="fa fa-users"></i>
                </div>
                <div>
                    <h3 class="t-white">Users</h3>
                    <h5 class="t-grey">We have <span
                            class="t-orange"><?php echo $pdo->query("SELECT COUNT(*) AS userCount FROM info_users")->fetch()["userCount"]; ?></span>
                        users (<span
                            class="t-blue"><?php echo $pdo->query("SELECT COUNT(*) AS userCount FROM info_users WHERE referralID != 1")->fetch()["userCount"]; ?>
                        </span>from<span class="t-blue"> referrals</span>)
                    </h5>
                </div>
            </div>
            <div class="illustr-box bg-dark col-lg-6 col-xs-12">
                <div class="illustr">
                    <i class="fab fa-bitcoin"></i>
                </div>
                <div>
                    <h3 class="t-white">Total Distributed</h3>
                    <h5 class="t-grey"><span class="t-orange"><?php 
                        $incomeTypes = $pdo->query("SELECT SUM(sats_balance) AS totalUserBalances,SUM(referralIncome) AS totalReferralIncome,SUM(multiplierSatsEarned) AS totalBonusEarned FROM info_users")->fetch();
                        echo $incomeTypes["totalUserBalances"]; ?>
                            sats</span>
                        (<span class="t-blue"><?php echo $incomeTypes["totalReferralIncome"]; ?>
                        </span>referral commission, <span
                            class="t-blue"><?php echo round($incomeTypes["totalBonusEarned"]); ?></span> from bonuses)
                    </h5>
                </div>
            </div>
        </div>
        <div class="mobile-wrap flex gap-1em">
            <div class="illustr-box bg-dark col-lg-6 col-xs-12">
                <div class="illustr">
                    <i class="fa fa-faucet"></i>
                </div>
                <div>
                    <h3 class="t-white">Faucet Claims</h3>
                    <h5 class="t-grey"><span class="t-orange"><?php
                                $faucetClaims = $pdo->query("SELECT COUNT(*) AS claimCount, SUM(claimAmount) AS totalClaimAmount FROM logs_claims")->fetch();
                                echo $faucetClaims["claimCount"]; ?></span>
                        claims (<span class="t-blue"><?php echo $faucetClaims["totalClaimAmount"]; ?>
                        </span>satoshis claimed)
                    </h5>
                </div>
            </div>
            <div class="illustr-box bg-dark col-lg-6 col-xs-12">
                <div class="illustr">
                    <i class="fa fa-comment-alt"></i>
                </div>
                <div>
                    <h3 class="t-white">Messages</h3>
                    <h5 class="t-grey"><span class="t-orange"><?php 
                        echo $pdo->query("SELECT COUNT(*) AS topicsCount FROM contact_topics")->fetch()["topicsCount"];
                        ?>
                        </span>topics created,
                        <span class="t-orange">
                            <?php echo $pdo->query("SELECT COUNT(*) AS messagesCount FROM contact_messages")->fetch()["messagesCount"]; ?>
                        </span> messages sent
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-9 col-xs-12 mt-5">
        <h1 class="mb-1">Support Messages</h1>
        <?php
        $userMessages = $pdo->query("SELECT * FROM contact_topics WHERE supportRead = 0 ORDER BY
        creationTimestamp DESC LIMIT 50")->fetchAll();
        ?>

        <div id="unreadmessages">
            <?php if(count($userMessages) > 0) { ?>
            <div class="message-list mb-5">
                <div class="list-header">
                    <h3>Unread Messages</h3>
                </div>
                <?php foreach($userMessages as $msg){ ?>
                <a class="list-element" href="../thread.php?topicID=<?php echo $msg['id']; ?>&supportMode=1">
                    <div>
                        <h5><?php echo htmlspecialchars($msg["topicTitle"]); ?> <span
                                class="badge badge-secondary">Unread</span></h5>
                    </div>
                </a>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <?php
        $userMessages = $pdo->query("SELECT * FROM contact_topics ORDER BY
        creationTimestamp DESC LIMIT 10")->fetchAll();
        ?>

        <div>
            <?php if(count($userMessages) > 0) { ?>
            <div class="message-list mb-5">
                <div class="list-header">
                    <h3>Last Topics</h3>
                </div>
                <?php foreach($userMessages as $msg){ ?>
                <a class="list-element" href="../thread.php?topicID=<?php echo $msg['id']; ?>&supportMode=1">
                    <div>
                        <h5><?php echo htmlspecialchars($msg["topicTitle"]);
                    if($msg["closed"] == 0) echo ' <span class="badge badge-success">Opened</span>';
                    else echo ' <span class="badge badge-danger">Closed</span>'; ?></h5>
                    </div>
                </a>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <h1 class="mb-1 mt-5">Last Claims</h1>
        <?php
        $userMessages = $pdo->query("SELECT * FROM logs_claims ORDER BY
        timestamp DESC LIMIT 10")->fetchAll();
        ?>

        <div>
            <?php if(count($userMessages) > 0) { ?>
            <div class="message-list mb-5">
                <div class="list-header">
                    <h3>10 last claims</h3>
                </div>
                <?php foreach($userMessages as $claim){ ?>
                <div class="list-element">
                    <h5><?php echo $claim["claimAmount"]; ?> claimed
                        <span class="badge badge-secondary"><?php echo time_elapsed_string("@".$claim["timestamp"]); ?>
                        </span> <span class="badge badge-info">UserID:
                            <?php echo htmlspecialchars($claim["userID"]); ?></span>
                    </h5>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <h1 class="mb-1 mt-5">Users Account</h1>
        <?php
        $userMessages = $pdo->query("SELECT * FROM info_users ORDER BY
        id DESC LIMIT 10")->fetchAll();
        ?>

        <div>
            <?php if(count($userMessages) > 0) { ?>
            <div class="message-list mb-5">
                <div class="list-header">
                    <h3>10 recent Users</h3>
                </div>
                <?php foreach($userMessages as $user){ ?>
                <div class="list-element">
                    <h5><?php echo htmlspecialchars($user["username"]); ?>
                        <span class="badge badge-secondary">Balance:
                            <?php echo $user["sats_balance"]; ?>
                        </span> <?php if($user["referralID"] != 1) { ?><span
                            class="badge badge-success">Referred</span><?php } ?>
                    </h5>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>
        <?php
        $userMessages = $pdo->query("SELECT * FROM info_users ORDER BY
        sats_balance DESC LIMIT 10")->fetchAll();
        ?>

        <div class="mt-4">
            <?php if(count($userMessages) > 0) { ?>
            <div class="message-list mb-5">
                <div class="list-header">
                    <h3>10 Richest Users</h3>
                </div>
                <?php foreach($userMessages as $user){ ?>
                <div class="list-element">
                    <h5><?php echo htmlspecialchars($user["username"]); ?>
                        <span class="badge badge-secondary">Balance:
                            <?php echo $user["sats_balance"]; ?>
                        </span> <?php if($user["referralID"] != 1) { ?><span
                            class="badge badge-success">Referred</span><?php } ?>
                    </h5>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
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
include_once('../../src/components/footer.php');
?>