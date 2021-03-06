<?php
    $originPath = "client/thread";

    include_once('../../src/config/pdo.php');
    include_once('../../src/components/functions.php');

    $userInfos = getUserInfos($pdo);

    $topicID = intval(htmlspecialchars($_POST["topicID"]));
    $topicInfos = $pdo->query("SELECT * FROM contact_topics WHERE id = $topicID ORDER BY creationTimestamp DESC")->fetch();
    $lastTopicMessages = $pdo->query("SELECT * FROM contact_messages WHERE topicID = $topicID ORDER BY publicationTimestamp DESC LIMIT 0,4")->fetchAll();

    $topicContent = htmlspecialchars($_POST["messageContent"]);
    $userType = intval(htmlspecialchars($_POST["userType"] ?? 0));

    if(!$topicInfos) $errorMessage = "Topic ID not found or deleted.";
    if($topicInfos["authorID"] != $userInfos["id"] && $userInfos["rank"] < 90) $errorMessage = "Could not authenticate user.";
    if($userType != 0 && $userInfos["rank"] < 90) {
        $errorMessage = "You can't send this message as a support team member.";
    }

    if($userType == 0) {
        $pdo->query("UPDATE contact_topics SET supportRead = 0 WHERE id = $topicID");
    } else {
        $pdo->query("UPDATE contact_topics SET userRead = 0 WHERE id = $topicID");
    }

    if($userType != 0) {
        $messageAction = htmlspecialchars($_POST["messageAction"]);
        if($messageAction == "close") {
            $pdo->query("UPDATE contact_topics SET closed = 1 WHERE id = $topicID");
            $topicContent = "This conversation has been closed. If you need further help, please start a new one.";
        } elseif($messageAction == "ban") {
            $pdo->query('UPDATE info_users SET bannedTimestamp = '.(time() + 1814400).' WHERE id = '.$topicInfos["authorID"]);
            $topicContent = "The team member in charge of this conversation has decided to ban the user for 3 weeks.";
        }
    }

    if($topicInfos["closed"] == 1) $errorMessage = "Topic has been closed. You can't send new messages.";
    if(strlen($topicContent) < 2) $errorMessage = "Your message is too short.";
    if(strlen($topicContent) > 40000) $errorMessage = "Your message is too long.";
    if($lastTopicMessages[0]["messageContent"] == $topicContent) $errorMessage = "You can't send the same message twice. If you are trying to spam, you will get banned.";

    if(count($lastTopicMessages) >= 4) {
        
        if(time() - $lastTopicMessages[3]["publicationTimestamp"] <= 10) {
            $pdo->query('UPDATE info_users SET bannedTimestamp = '.(time() + (7200*2)).' WHERE id = '.$userInfos["id"]);
            echo "You have been banned from our website for spamming.";
        }
        elseif(time() - $lastTopicMessages[2]["publicationTimestamp"] <= 7) $errorMessage = "Please stop spamming. Next time we will have to ban you.";
        elseif(time() - $lastTopicMessages[1]["publicationTimestamp"] <= 5) $errorMessage = "Please stop spamming. If you continue to spam you will get automatically banned.";

    }

    if(isset($errorMessage)) {
        echo $errorMessage;
        die();
    } else {
        
        $createContactMessage_stmt = $pdo->prepare("INSERT INTO contact_messages (messageContent, authorID, publicationTimestamp, topicID, userType) VALUES (:content, ".$userInfos["id"].", ".time().",$topicID,$userType)");
        $createContactMessage_stmt->bindParam(':content', $topicContent);
        $createContactMessage_stmt->execute();

    }

?>