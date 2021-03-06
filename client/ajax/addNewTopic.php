<?php
include_once('../../src/config/pdo.php');
include_once('../../src/components/functions.php');

$userInfos = getUserInfos($pdo);
$userLastTopic = $pdo->query("SELECT * FROM contact_topics WHERE authorID = ".$userInfos["id"]." ORDER BY creationTimestamp DESC LIMIT 0,1")->fetch();

$topicTitle = htmlspecialchars($_POST["topicTitle"]);
$topicContent = htmlspecialchars($_POST["topicContent"]);

if(time() - $userLastTopic["creationTimestamp"] <= 480) {
    echo "<div class='alert alert-danger'>You are being ratelimited. You can create a new topic every 8 minutes. If you are spamming or flooding with useless messages, you will be banned from our website.</div>";
    die();
}

if(strlen($topicTitle) < 1) $topicTitle = "No title provided";

$createContactTopic_stmt = $pdo->prepare("INSERT INTO contact_topics (topicTitle, authorID, creationTimestamp, closed, claimedBy) VALUES (:title, ".$userInfos["id"].", ".time().", 0, 0)");
$createContactTopic_stmt->bindParam(':title', $topicTitle);

$createContactTopic_stmt->execute();
$topicID = $pdo->lastInsertId();

$createContactMessage_stmt = $pdo->prepare("INSERT INTO contact_messages (messageContent, authorID, publicationTimestamp, topicID) VALUES (:content, ".$userInfos["id"].", ".time().",$topicID)");
$createContactMessage_stmt->bindParam(':content', $topicContent);
$createContactMessage_stmt->execute();

header("Location: ../thread.php?topicID=".$topicID);
?>