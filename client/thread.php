<?php
    $originPath = "client/thread";
    include_once('../src/components/header.php');    
    $topicID = intval(htmlspecialchars($_GET["topicID"]));
    $topicInfos = $pdo->query("SELECT * FROM contact_topics WHERE id = $topicID ORDER BY creationTimestamp DESC")->fetch();

    $redirectUser = false;
    if(!$topicInfos) $redirectUser = true;
    if($topicInfos["authorID"] != $userInfos["id"] && $userInfos["rank"] < 90) $redirectUser = true;

    $supportMode = ($_GET["supportMode"] == 1 ? true : false);
    if($supportMode && $userInfos["rank"] < 90) $redirectUser = true;

    if($redirectUser) {
        header('Location: contact.php');
        die();
    }

    if($supportMode) {
        $pdo->query("UPDATE contact_topics SET supportRead = 1 WHERE id = $topicID");
        $threadAuthor = getUserInfos($pdo, $topicInfos["authorID"]);
    } else {
        $pdo->query("UPDATE contact_topics SET userRead = 1 WHERE id = $topicID");
        $threadAuthor = $userInfos;
    }

    $topicMessages = $pdo->query("SELECT * FROM contact_messages WHERE topicID = $topicID ORDER BY publicationTimestamp ASC")->fetchAll();
    $pdo = null; // Killing pdo to ensure nobody can access it if using a possible xss breach
    ?>

<div class="global-container relative">
    <div class="topic-name flex wrap align-center">
        <?php if($supportMode) { ?><a href="admin/dashboard.php#unreadmessages" class="mt-2 mb-2"><span
                class="button mr-3">Go
                Back</span></a><?php } ?>
        <h3>
            <?php echo htmlspecialchars($topicInfos["topicTitle"]); ?></h3>
    </div>

    <div class="message-display flex <?php if($supportMode) echo "end"; ?> wrap">
        <h6 class="message-name w-100p <?php if($supportMode) echo "text-right"; ?>">
            <?php echo htmlspecialchars($threadAuthor["username"]); ?> started a
            new topic</h6>
        <div class="message-content">
            <?php echo str_replace("\n", "<br>", $topicMessages[0]["messageContent"]); ?></div>
    </div>

    <?php 
    $i = 0;
    foreach($topicMessages as $msg) { 
        $i++;
        if($i == 1) continue;
        $displayRight = (($supportMode ? 1 : 0) != ($msg["userType"] == 1 ? 1 : 0) ? true : false);
    ?>
    <div class="message-display flex wrap <?php if($displayRight) echo "end"; ?>">
        <h6 class="message-name w-100p <?php if($displayRight) echo "text-right"; ?>">
            <?php echo ($msg["userType"] == 0 || $supportMode ? 
            ($msg["userType"] == 0 && $supportMode ? htmlspecialchars($threadAuthor["username"]) : htmlspecialchars($userInfos["username"])) : "Support <i class='fa fa-user-cog'></i>"); ?>
        </h6>
        <div class="message-content">
            <?php echo str_replace("\n", "<br>", $msg["messageContent"]); ?></div>
    </div>
    <?php } ?>

    <div id="newMessagesContainer"></div>

    <?php if($topicInfos["closed"] == 0){ ?>
    <div class="message-display flex wrap" id="newMessageInput">
        <h6 class="message-name w-100p" id="msgInput">Write a new message</h6>
        <div class="w-100-p">
            <div id="errorMessageContainer"></div>
            <div class="w-100-p">
                <textarea class="message-content"
                    id="newMessageContent"><?php if($supportMode) { echo "\n\n---\nSincerely,\n[FAUCET NAME]'s support team."; } ?></textarea>
            </div>
            <div class="flex gap-04em">
                <div class="btn btn-dark" onclick="sendNewMessage();">Send</div>
                <?php if($supportMode) { ?>
                <div class="btn btn-danger" onclick="sendNewMessage('close'); closeThread();">Close</div>
                <div class="btn btn-danger" onclick="sendNewMessage('ban');">Ban</div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<script>
function sendNewMessage(actionType = null) {
    $.ajax({
        type: "POST",
        url: "ajax/addNewMessage.php",
        data: {
            messageContent: $("#newMessageContent").val(),
            topicID: <?php echo $topicID; ?>,
            userType: <?php echo ($supportMode ? 1 : 0) ?>
            <?php if($supportMode) echo ",messageAction: actionType"; ?>,
        },
        success: (errorMessage) => {
            let messageContent = $("#newMessageContent").val()
                .replace(/</gm, "").replace(/(?:\r\n|\r|\n)/g, '<br>');

            <?php if($supportMode) { ?>
            // Check if any action has been taken
            if (actionType) {
                if (actionType == "ban") {
                    messageContent =
                        "You banned this user for 3 weeks. Please provide a reason and close the discussion.";
                } else return;
            }
            <?php } ?>
            if (errorMessage) {
                $("#errorMessageContainer").html(
                    `<div class="alert alert-danger col-lg-4 col-xs-12">${errorMessage}</div>`);
            } else {
                $("#newMessagesContainer").html(`${$("#newMessagesContainer").html()}<div class="message-display flex wrap">
                        <h6 class="message-name w-100p"><?php echo htmlspecialchars($userInfos["username"]); ?></h6>
                        <div class="message-content">${messageContent}</div>
                    </div>`);
                $("#errorMessageContainer").html("");
                $("#newMessageContent").val(" ");
                window.scrollTo(0, document.body.scrollHeight);
            }
        },
    });
}

function closeThread() {
    $("#newMessageInput").hide("1000");
}

window.onload = (() => {
    setTimeout(() => {
        window.scrollTo(0, document.body.scrollHeight);
    }, 300);
});
</script>
