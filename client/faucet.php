<?php
    $originPath = "client/faucet";
    include_once('../src/components/header.php');

    $claimReady = (($userInfos["lastClaimTimestamp"]+$userInfos["faucetTimer"]) <= time());
?>

<div class="global-container flex space-btw">
    <div class="col-lg-2 flex center mt-4 d-sm-n">
        <?php displayAdvert("long-banner", 3, $userInfos["userLevel"]); ?>
    </div>
    <div class="responsive-container flex center wrap">
        <div class="mt-5 mb-4 w-100-p flex center">
            <div>
                <div class="flex center">
                    <h1 id="counter" class="faucet-counter <?php if($claimReady) echo "ready"; ?>">
                        <?php if($claimReady) echo "Ready !"; else echo '<i class="fa fa-history"></i> ...'; ?></h1>
                </div>
                <div id="claimMessage"></div>
            </div>
        </div>
        <?php if($userInfos["userLevel"] >= 23) { ?>
        <div class="mb-4 mt-3 w-100-p flex center wrap gap-06em">
            <?php displayAdvert("rectangle", 7, $userInfos["userLevel"]); ?>
            <?php displayAdvert("rectangle", 11, $userInfos["userLevel"]); ?>
        </div>
        <?php } ?>
        <div class="w-100-p text-center">
            <h4 class="mb-3 ml-2 mr-2" id="claimText1">
                <?php if($claimReady) { ?>
                The faucet is ready !<br>Complete the captcha and claim your <i class="fab fa-bitcoin"></i>
                <?php } else { ?>
                The faucet is not ready yet, you can reduce the claim delay by playing dice!
                <?php } ?>
            </h4>
        </div>
        <div class="mb-4 mt-2 flex gap-06em mobile-wrap">
            <?php displayAdvert("square", 0, 0); ?>
            <?php displayAdvert("square", 0, 0); ?>
        </div>
        <?php
        if($claimReady) { ?>
        <div id="faucetRecaptcha" class="w-100-p flex center">
            <div class="h-captcha" data-sitekey="37de5ef0-4724-4dc7-9928-4e9572cf4f9f"></div>
        </div>
        <div class="mb-4 mt-3 w-100-p flex center wrap gap-06em">
            <?php displayAdvert("rectangle", 5, $userInfos["userLevel"]); ?>
            <?php displayAdvert("rectangle", 9, $userInfos["userLevel"]); ?>
        </div>
        <div id="claimButton" class="button blue">
            <i class="fa fa-clock"></i> ...
        </div>
        <div class="mb-4 mt-3 w-100-p flex center wrap gap-06em">
            <?php displayAdvert("banner", 12, $userInfos["userLevel"]); ?>
            <?php displayAdvert("banner", 15, $userInfos["userLevel"]); ?>
        </div>
        <?php } ?>
    </div>
    <div class="col-lg-2 flex center mt-4 d-sm-n gap-06em">
        <?php displayAdvert("long-banner", 3, $userInfos["userLevel"]); ?>
    </div>
</div>

<?php if($claimReady) { ?>
<script>
let gainExamples = ["2 sats", "1 sats", "3 sats", "4 sats", "5 sats", "6 sats", "7 sats", "8 sats", "9 sats",
    "10 sats",
    "2 sats", "25 sats", "26 sats", "27 sats", "3 sats", "4 sats", "5 sats", "6 sats", "7 sats", "8 sats",
    "9 sats",
    "10 sats", "24 sats", "19 sats", "21 sats", "22 sats", "5 sats", "6 sats", "7 sats", "8 sats", "9 sats",
    "10 sats", "15 sats", "20 sats", "30 sats", "50 sats", "70 sats", "100 sats", "200 sats", "500 sats",
    "1 000 sats", "1 500 sats", "10 000 sats", "300 000 sats", "22 sats", "11 sats", "12 sats", "13 sats",
    "14 sats", "16 sats", "17 sats", "35 sats", "40 sats", "31 sats", "32 sats", "33 sats", "34 sats",
    "35 sats",
    "36 sats", "37 sats", "38 sats", "39 sats", "40 sats", "41 sats", "42 sats", "43 sats", "44 sats",
    "45 sats",
    "46 sats"
]
let counter = document.getElementById('counter');
let counterExampleInterval = setInterval(() => {
    counter.innerHTML = gainExamples[Math.floor((Math.random()) * (gainExamples.length - 1))];
}, 200);

window.onload = (() => {
    let claimButton = document.getElementById('claimButton');
    let remainingSeconds = 10;
    let claimTimer = setInterval(() => {
        remainingSeconds = remainingSeconds - 1;
        claimButton.innerHTML = `<i class="fa fa-clock"></i> ${remainingSeconds}s`;
        if (remainingSeconds <= 0) {
            claimTimerDone();
        }
    }, 1000);

    function claimTimerDone() {
        clearInterval(claimTimer);
        claimButton.innerHTML = `<i class="fa fa-check"></i> Claim`;
        claimButton.addEventListener("click", () => {
            claimSatoshis()
        });
    }
});

let completeCaptchaCode = "";
rainCaptcha.on('complete', function(data) {
    completeCaptchaCode = data;
});

function claimSatoshis() {
    $.ajax({
        type: "POST",
        url: "ajax/claimFaucet.php",
        data: {
            "h-captcha-response": $('[name=h-captcha-response]').val()
        },
        success: (returnValue) => {
            let claimedMessage = document.getElementById('claimMessage');

            if (returnValue == "NO-CAPTCHA") {
                return claimedMessage.innerHTML =
                    `<div class="error-claim-message"><span class="bold">Whoops!</span> Don't forget to complete the captcha...</div>`;

            } else if(returnValue == "FAUCET-DISABLED") {
                return claimedMessage.innerHTML = 
                    `<div class="error-claim-message">You cannot claim anymore.. We're shutting the website down :(</div>`;

            }

            clearInterval(counterExampleInterval);
            counter.innerHTML = `${returnValue} sats`;
            counter.classList.add("claimed");
            counter.classList.remove("ready");

            increaseBalance(returnValue);

            document.getElementById("faucetRecaptcha").style.display = "none";
            document.getElementById("claimButton").style.display = "none";
            document.getElementById("claimText1").innerHTML = "<span class='bold'>You just claimed " +
                returnValue +
                " satoshis !</span><br>Multiply your earnings by playing dice !";

            claimedMessage.innerHTML =
                `<div class="claim-message"><span class="claim-amount">Congrats!</span> You just claimed <span class="claim-amount">${returnValue} satoshis</span> and earned an extra <span class="xp-amount">${(Math.ceil(returnValue/15)+10)} XP</span> !</div>`;
            console.log(`Claimed ${returnValue} satoshis !`);
        },
        dataType: "HTML"
    });
}
</script>
<?php } else { ?>
<script>
var countDownDate = Date.now() + (
    <?php echo ($userInfos["lastClaimTimestamp"]+$userInfos["faucetTimer"]) - time(); ?> *
    1000);

var countdownInterval = setInterval(function() {

    var now = new Date().getTime();
    var distance = countDownDate - now;

    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    document.getElementById("counter").innerHTML = "<i class='fa fa-history'></i> " + minutes + "m " +
        seconds +
        "s ";

    if (distance < 0) {
        clearInterval(countdownInterval);
        document.getElementById("counter").innerHTML = "Ready !";
        document.location.reload();
    }
}, 1000);
</script>
<?php } ?>

<?php
include_once('../src/components/footer.php');
?>