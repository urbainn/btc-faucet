<?php
    $originPath = "client/contact";
    include_once('../src/components/header.php');
    
    $userMessages = $pdo->query("SELECT * FROM contact_topics WHERE authorID = ".$userInfos["id"]." ORDER BY creationTimestamp DESC")->fetchAll();
?>

<div class="global-container">
    <div class="responsive-container flex center wrap">
        <div class="col-lg-9 mt-5">
            <?php if(count($userMessages) > 0) { ?>
            <div class="message-list mb-5">
                <div class="list-header">
                    <h3>Your Messages</h3>
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

            <form action="ajax/addNewTopic.php" method="post">
                <h1>Contact Us</h1>
                <h3>We're always happy to help! If you have any questions, suggestions or problems, please feel free
                    to
                    contact us by using the form below.</h3>
                <input type="text" name="topicTitle" placeholder="Message Title" class="ref-input w-100-p mt-3 mb-2">
                <textarea name="topicContent"
                    placeholder="Write your message here, we'll get back to you in less than ~24h! We are French, so our English might not be perfect :)"
                    class="ref-input w-100-p"></textarea>
                <input type="submit" class="btn btn-dark" value="Start Conversation">
            </form>
        </div>
    </div>
</div>

<?php
include_once('../src/components/footer.php');
?>