<?php
    $originPath = "client/dice";
    include_once('../src/components/header.php');

    $claimReady = (($userInfos["lastClaimTimestamp"]+$userInfos["faucetTimer"]) <= time());
?>

<div class="global-container">
    <div class="flex center responsive-container">
        <div class="col-lg-9">
            <div id="rollDiceContainer" class="display-box dice-container bg-dark mt-5 mb-0 fade-in">
                <div class="flex center">
                    <h1 class="t-white"><i class="fa fa-dice"></i> Dice</h1>
                </div>
                <div class="dice-line flex">
                    <div class="dice-line-part" id="dicePart1"></div>
                    <div class="dice-line-part" id="dicePart2"></div>
                    <div class="dice-line-position" id="dicePosition"></div>
                </div>
                <div class="flex mt-4 mb-2 lg-center gap-2em mobile-wrap">
                    <div>
                        <h5 class="t-white bold mb-1">Bet Amount <span class="h6 bold t-grey">satoshis</span></h5>
                        <div class="flex gap-04em mobile-wrap">
                            <input id="betAmountInput"
                                onchange="increaseBetAmount(parseInt($('#betAmountInput').val()))" type="number"
                                class="dice-input" min="1" value="1"></input>
                            <div class="flex button-group">
                                <div class="dice-button button-groupped"
                                    onclick="increaseBetAmount(rollOptions.betAmount*2)">x2</div>
                                <div class="dice-button button-groupped"
                                    onclick="increaseBetAmount(rollOptions.betAmount/2)">/2</div>
                                <div class="dice-button button-groupped"
                                    onclick="increaseBetAmount(userSatoshisAmount)">Max
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="t-white bold mb-1 no-break">The dice will land on...</h5>
                        <div class="flex gap-04em">
                            <div class="dice-button green-option" id="greenOptionButton">green</div>
                            <div class="dice-button red-option" id="redOptionButton">red</div>
                        </div>
                    </div>
                </div>
                <div id="rollOptions" class="mt-4 display-none">
                    <div class="flex center gap-1em mobile-wrap">
                        <div class="auto-dice-container">
                            <h4 class="t-grey bold">On win...</h4>
                            <hr>
                            <h5 class="t-white bold mt-3 mb-1">Increase bet by <span class="h6 bold t-grey">%</span>
                            </h5>
                            <input id="betAmountInput"
                                onchange="rollOptions.onwin.increaseBetPercent = parseInt(this.value);" type="number"
                                class="dice-input" value="0"></input>
                            <div class="flex align-center mt-2 mb-1">
                                <input type="checkbox" onchange="rollOptions.onwin.reset = $(this).prop('checked');"
                                    class="diceRollCheckbox">
                                <h6 class="t-grey">Return to base bet</h6>
                            </div>
                            <h5 class="t-white bold mt-4 mb-1">Change bet color</h5>
                            <div class="flex gap-04em">
                                <div class="dice-button green-option" onclick="changeAutoBetColor('onwin', 0);"
                                    id="onwinGreenOption">Green</div>
                                <div class="dice-button red-option" onclick="changeAutoBetColor('onwin', 1);"
                                    id="onwinRedOption">Red</div>
                                <div class="dice-button" onclick="changeAutoBetColor('onwin', 2);"
                                    id="onwinRandomOption">
                                    Random</div>
                            </div>
                        </div>
                        <div class="auto-dice-container">
                            <h4 class="t-grey bold">On lose...</h4>
                            <hr>
                            <h5 class="t-white bold mt-3 mb-1">Increase bet by <span class="h6 bold t-grey">%</span>
                            </h5>
                            <input id="betAmountInput"
                                onchange="rollOptions.onloose.increaseBetPercent = parseInt(this.value);" type="number"
                                class="dice-input" value="0"></input>
                            <div class="flex align-center mt-2 mb-1">
                                <input type="checkbox" onchange="rollOptions.onloose.reset = $(this).prop('checked');"
                                    class="diceRollCheckbox">
                                <h6 class="t-grey">Return to base bet</h6>
                            </div>
                            <h5 class="t-white bold mt-4 mb-1">Change bet color</h5>
                            <div class="flex gap-04em">
                                <div class="dice-button green-option" onclick="changeAutoBetColor('onloose', 0);"
                                    id="onlooseGreenOption">Green</div>
                                <div class="dice-button red-option" onclick="changeAutoBetColor('onloose', 1);"
                                    id="onlooseRedOption">Red</div>
                                <div class="dice-button" onclick="changeAutoBetColor('onloose', 2);"
                                    id="onlooseRandomOption">
                                    Random</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex center mt-5 gap-06em">
                    <div class="dice-button button-gold-gradient" id="rollNowButton">Roll now</div>
                    <div class="dice-button button-red display-none" onclick="stopAutoBet()" id="stopAutoBetButton">Stop
                        Auto-Bet</div>
                    <div class="dice-button auto-button" onclick="toggleAutoMode();">Auto</div>
                </div>
            </div>
            <div id="diceRollSubcontainer" class="mb-4 dice-subcontainer flex btw">
                <div id="diceRollResult"></div>
                <div class="separator"></div>
                <div id="diceRollTotal"></div>
            </div>
            <div class="flex gap-1em mobile-wrap m-1 mt-3">
                <div class="illustr-box bg-dark col-lg-6 col-xs-12">
                    <div class="illustr">
                        <i class="fa fa-gift"></i>
                    </div>
                    <div class="w-100-p">
                        <h3 class="t-white">Claim a Gift</h3>
                        <div id="wagerGiftContainer">

                        </div>
                    </div>
                </div>
                <div class="illustr-box bg-dark col-lg-6 col-xs-12">
                    <div class="illustr">
                        <i class="fa fa-wallet"></i>
                    </div>
                    <div class="w-100-p">
                        <h3 class="t-white">Dice Cashback</h3>
                        <div id="diceCashbackDisplay">

                        </div>
                    </div>
                </div>
            </div>
            <?php echo "<script>let userGiftWager = ".$userInfos["wageredSinceGift"]."; "
        ."let diceCashbackReward = ".$userInfos["diceCashbackReward"].";"
        ."let diceCashbackMultiplier = ".$userInfos["diceCashback"]."</script>"; ?>
        </div>
    </div>
</div>

<script src="../src/js/client/dice.js"></script>

<!-- No balance left modal -->
<div class="modal fade" id="lowBalanceModal" tabindex="-1" role="dialog" aria-labelledby="lowBalanceModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lowBalanceModalLabel">You don't have enough satoshis...</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span class="bold">Oh no!</span> It looks like you don't have enough satoshis, bet a lower amount or go
                to our faucet to refill your balance !
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="faucet.php"><button type="button" class="btn btn-primary">Go to faucet</button></a>
            </div>
        </div>
    </div>
</div>

<?php
include_once('../src/components/footer.php');
?>