<?php
    $originPath = "client/referrals";
    include_once('../src/components/header.php');

    $claimReady = (($userInfos["lastClaimTimestamp"]+$userInfos["faucetTimer"]) <= time());
    $referralsList = $pdo->query("SELECT id FROM info_users WHERE referralID = ".$userInfos["id"])->fetchAll();
?>

<div class="global-container responsive-container">
    <div class="flex center mt-5">
        <div class="col-lg-9 flex gap-1em mobile-wrap">
            <div class="illustr-box bg-dark w-100p col-xs-12">
                <div class="illustr">
                    <i class="fa fa-users"></i>
                </div>
                <div>
                    <h3 class="t-white">Referrals</h3>
                    <h5 class="t-grey">You have <span class="t-orange"><?php echo count($referralsList); ?></span>
                        active <span class="t-orange">referrals</span>.
                    </h5>
                </div>
            </div>
            <div class="illustr-box bg-dark w-100p col-xs-12">
                <div class="illustr">
                    <i class="fa fa-wallet"></i>
                </div>
                <div>
                    <h3 class="t-white">Referral Income</h3>
                    <h5 class="t-grey">You earned <span class="t-orange"><?php echo $userInfos["referralIncome"] ?>
                            satoshis</span>
                    </h5>
                </div>
            </div>
        </div>
    </div>
    <div class="flex center w-100-p">
        <div class="col-lg-9">
            <div class="referral-message bg-dark mt-4 w-100p">
                <h5 class="t-grey text-center"><span class="bold">Earn <span class="t-blue bold">10%</span> of all your
                        referral's faucet claims,
                        and <span class="t-blue">1XP</span> bonus for each faucet claim !</span><br>Earn up to <span
                        class="t-orange">30k satoshis</span> in a single referral claim !<br><br>Share this link with
                    your
                    friends, family, subscribers...</h5>

                <div class="flex w-100-p center mt-3"><input class="ref-input"
                        value="http://[FAUCET NAME].com?r=<?php echo $userInfos["id"]; ?>" id="referralCodeInput">
                    <div class="ref-input ml-1" onclick="copyElement('referralCodeInput', 'button_7S6J09');"
                        id="button_7S6J09"><i class="fa fa-file-import"></i></div>
                </div>
            </div>
            <div class="alert alert-warning mt-3">Creating alt accounts is not allowed. If you do so, you will lose all your active referrals, your balance will be reset and your main account banned.</div>

            <h1 class="mt-5">Promo Materials:</h1>
            <!-- Promo Banner 1 -->
            <img class="w-100-p promo-image mt-2" src="https://i.imgur.com/6Pl4pcW.png">
            <h5 class="mt-3 mb-2 bold">Direct image link:</h5>
            <div class="flex">
                <input type="text" id="promoMaterial_1" class="w-100-p ref-input"
                    value="https://i.imgur.com/6Pl4pcW.png">
                <div class="ref-input ml-1" id="promoMaterialCopy_1"
                    onclick="copyElement('promoMaterial_1', 'promoMaterialCopy_1');"><i class="fa fa-file-import"></i>
                </div>
            </div>
            <h5 class="mt-3 mb-2 bold">Website integration <span class="t-grey">with your referral link !</span></h5>
            <div class="flex">
                <input type="text" id="promoMaterial_2" class="w-100-p ref-input"
                    value='<a href="http://[FAUCET NAME].com?r=<?php echo $userInfos["id"]; ?>" target="_blank"><img src="https://i.imgur.com/6Pl4pcW.png" alt="[FAUCET NAME] Faucet"></a>'>
                <div class="ref-input ml-1" id="promoMaterialCopy_2"
                    onclick="copyElement('promoMaterial_2', 'promoMaterialCopy_2');"><i class="fa fa-file-import"></i>
                </div>
            </div>

            <!-- Promo banner 2 -->
            <img class="promo-image mt-5" src="https://i.imgur.com/GurlpXh.png">
            <h5 class="mt-3 mb-2 bold">Direct image link:</h5>
            <div class="flex">
                <input type="text" id="promoMaterial_3" class="w-100-p ref-input"
                    value="https://i.imgur.com/GurlpXh.png">
                <div class="ref-input ml-1" id="promoMaterialCopy_3"
                    onclick="copyElement('promoMaterial_3', 'promoMaterialCopy_3');"><i class="fa fa-file-import"></i>
                </div>
            </div>
            <h5 class="mt-3 mb-2 bold">Website integration <span class="t-grey">with your referral link !</span></h5>
            <div class="flex mb-5">
                <input type="text" id="promoMaterial_4" class="w-100-p ref-input"
                    value='<a href="http://[FAUCET NAME].com?r=<?php echo $userInfos["id"]; ?>" target="_blank"><img src="https://i.imgur.com/GurlpXh.png" alt="[FAUCET NAME] Faucet"></a>'>
                <div class="ref-input ml-1" id="promoMaterialCopy_4"
                    onclick="copyElement('promoMaterial_4', 'promoMaterialCopy_4');"><i class="fa fa-file-import"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyElement(inputID, buttonID) {
    let copyText = document.getElementById(inputID);
    let copyButton = document.getElementById(buttonID);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");
    copyButton.classList.add("copied");
    copyButton.innerHTML = "<i class='fa fa-check'></i>";
}
</script>

<?php
include_once('../src/components/footer.php');
?>