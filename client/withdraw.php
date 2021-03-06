<?php
    $originPath = "client/withdraw";
    include_once('../src/components/header.php');

    $claimReady = (($userInfos["lastClaimTimestamp"]+$userInfos["faucetTimer"]) <= time());
    $referralsList = $pdo->query("SELECT id FROM info_users WHERE referralID = ".$userInfos["id"])->fetchAll();
?>

<div class="global-container responsive-container">
    <div class="flex center mt-5">
        <div class="col-lg-9">
            <h2>Edit withdraw address</h2>
            <div id="errorMessageContainer" class="mt-2"></div>
            <input type="text" id="BTC_payoutWalletAddress" value="<?php echo $userInfos["linkedBtcAddress"]; ?>"
                class="mb-1" placeholder="FaucetPay Bitcoin Address">
            <h6 class="t-grey mb-3">You need to enter the bitcoin wallet address associated with your <a
                    href="https://faucetpay.io/?r=144281">FaucetPay.io</a> account.</h6>

            <div class="flex gap-06em">
                <div class="btn btn-success" onclick="saveNewWalletAddress()">Save</div>
                <div class="btn btn-secondary" onclick="withdrawFunds()">Withdraw all Balance</div>
            </div>
        </div>
    </div>
</div>

<script>
function saveNewWalletAddress() {
    $.ajax({
        type: "POST",
        url: "ajax/editWalletAddress.php",
        data: {
            walletAddress: $('#BTC_payoutWalletAddress').val()
        },
        success: (returnValue) => {
            if (returnValue) {
                $("#errorMessageContainer").html(`<div class='alert alert-danger'>${returnValue}</div>`);
            } else {
                $("#errorMessageContainer").html(
                    `<div class='alert alert-success'>Wallet Address updated with success !</div>`);
            }
        }
    })
};

function withdrawFunds() {
    $.ajax({
        type: "POST",
        url: "ajax/withdrawFunds.php",
        success: (returnValue) => {
            if (returnValue) {
                $("#errorMessageContainer").html(`<div class='alert alert-danger'>${returnValue}</div>`);
            } else {
                $("#errorMessageContainer").html(
                    `<div class='alert alert-success'>All your funds have been sent to your FaucetHub account ! Congratulations :)</div>`
                );
                increaseBalance(0 - userSatoshisAmount); // Set balance to 0 (frontend of course)
            }
        }
    })
};
</script>

<?php
include_once('../src/components/footer.php');
?>