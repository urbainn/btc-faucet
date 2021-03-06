let dicePart1 = document.getElementById("dicePart1");
let dicePart2 = document.getElementById("dicePart2");
let dicePartPosition = document.getElementById("dicePosition");
let displayNoUpdate = false;

updateWagerGift();

dicePart1.style.backgroundColor = "#2cd192";
dicePart2.style.backgroundColor = "#e86456";
dicePartPosition.style.left = `calc(50% - ${
  dicePartPosition.offsetWidth / 2
}px)`;

let greenOptionButton = document.getElementById("greenOptionButton");
let redOptionButton = document.getElementById("redOptionButton");

// 0 = green; 1 = red;
let selectedBetColor = 0;
greenOptionButton.classList.add("active");

greenOptionButton.addEventListener("click", () => {
  changeBetColor(rollOptions.betColor == 0 ? 1 : 0);
});

redOptionButton.addEventListener("click", () => {
  changeBetColor(rollOptions.betColor == 1 ? 0 : 1);
});

let rollOptions = {
  baseBetAmount: 1,
  betAmount: 1,
  betColor: 0,
  rollNumber: 0,
  lastRoll: 0,
  autoMonde: false,
  lastRollWin: false,
  onloose: {
    increaseBetPercent: 0,
    reset: false,
    changeBetColor: false, // 0 = green, 1 = red, 2 = random, false = none
  },
  onwin: {
    increaseBetPercent: 0,
    reset: false,
    changeBetColor: false,
  },
};

document.getElementById("rollNowButton").addEventListener("click", () => {
  rollDice();
});

let diceSubcontainer = $("#diceRollSubcontainer");

function rollDice(isAuto = false) {
  if (rollOptions.betAmount > userSatoshisAmount) {
    $("#lowBalanceModal").modal("toggle");
    if (rollOptions.autoMonde) {
      stopAutoBet();
    }
    return;
  }

  if (rollOptions.autoMonde == true && isAuto == false) {
    return lauchAutoBet();
  }
  $.ajax({
    type: "POST",
    url: "ajax/rollDice.php",
    data: {
      betColor: rollOptions.betColor,
      betAmount: rollOptions.betAmount,
    },
    success: (numberRolled) => {
      userGiftWager += rollOptions.betAmount;
      updateWagerGift();

      rollOptions.lastRoll = Date.now();
      $("#dicePosition").animate({ left: `${numberRolled}%` }, 300);
      if (numberRolled < 50) {
        // Rolled green
        if (rollOptions.betColor == 0) {
          // User won !
          userWonRoll(rollOptions);
        } else {
          // User lost..
          userLostRoll(rollOptions);
        }
      } else if (numberRolled > 50) {
        // Rolled red
        if (rollOptions.betColor == 1) {
          // User won !
          userWonRoll(rollOptions);
        } else {
          // User lost..
          userLostRoll(rollOptions);
        }
      }

      if (rollOptions.rollNumber <= 0) {
        document
          .getElementById("rollDiceContainer")
          .classList.add("no-bottom-radius");

        diceSubcontainer.show(700);
      }
      rollOptions.rollNumber++;
    },
  });
}

function userWonRoll(rollOptions) {
  $("#diceRollResult").html(
    `<h5 class="bold t-grey">You won <span class="greenResult">${
      rollOptions.betAmount * 2
    } satoshis</span></h5>`
  );

  increaseBalance(rollOptions.betAmount);
  rollOptions.lastRollWin = true;

  if (rollOptions.autoMonde) {
    if (rollOptions.onwin.increaseBetPercent && !rollOptions.onwin.reset) {
      increaseBetAmount(
        rollOptions.betAmount +
          (rollOptions.betAmount / 100) * rollOptions.onwin.increaseBetPercent,
        true
      );
    } else if (rollOptions.onwin.reset)
      increaseBetAmount(rollOptions.baseBetAmount);

    if (rollOptions.onwin.changeBetColor !== false) {
      if (rollOptions.onwin.changeBetColor == 2) {
        changeBetColor(Math.round(Math.random()));
      } else {
        changeBetColor(rollOptions.onwin.changeBetColor);
      }
    }
  }
}

function userLostRoll(rollOptions) {
  $("#diceRollResult").html(
    `<h5 class="bold t-grey">You lost <span class="redResult">${rollOptions.betAmount} satoshis</span></h5>`
  );

  diceCashbackReward += (rollOptions.betAmount / 100) * diceCashbackMultiplier;
  userDiceRollback = true;

  increaseBalance(0 - rollOptions.betAmount);
  rollOptions.lastRollWin = false;

  if (rollOptions.autoMonde) {
    if (rollOptions.onloose.increaseBetPercent && !rollOptions.onloose.reset) {
      increaseBetAmount(
        rollOptions.betAmount +
          (rollOptions.betAmount / 100) *
            rollOptions.onloose.increaseBetPercent,
        true
      );
    } else if (rollOptions.onloose.reset)
      increaseBetAmount(rollOptions.baseBetAmount);

    if (rollOptions.onloose.changeBetColor !== false) {
      if (rollOptions.onloose.changeBetColor == 2) {
        changeBetColor(Math.round(Math.random()));
      } else {
        changeBetColor(rollOptions.onloose.changeBetColor);
      }
    }
  }
}

function toggleAutoMode() {
  rollOptions.autoMonde = rollOptions.autoMonde == false ? true : false;

  if (rollOptions.autoMonde == true) {
    $("#rollOptions").show(1000);
    $("#betColorButtons").hide(500);
    $("#rollNowButton").html("Start Auto-Bet");
  } else {
    stopAutoBet();
    $("#rollOptions").hide(1000);
    $("#betColorButtons").show(500);
    $("#rollNowButton").html("Roll now");
  }
}

// Change bet color
function changeBetColor(betColor, choseRandomColor = false) {
  if (choseRandomColor) {
    rollOptions.betColor = Math.round(Math.random());
  } else {
    rollOptions.betColor = betColor;
  }

  if (rollOptions.betColor == 0) {
    greenOptionButton.classList.add("active");
    redOptionButton.classList.remove("active");
  } else {
    greenOptionButton.classList.remove("active");
    redOptionButton.classList.add("active");
  }
}

// lauch auto bet
let autoBetInterval;
function lauchAutoBet() {
  $("#rollNowButton").hide();
  $("#stopAutoBetButton").show();

  autoBetInterval = setInterval(() => {
    rollDice(true);
  }, 830);
}

// Stop auto bet
function stopAutoBet() {
  $("#rollNowButton").show();
  $("#stopAutoBetButton").hide();

  clearInterval(autoBetInterval);
}

// Update user gift
function updateWagerGift(displayAgain = false) {
  if (displayAgain) displayNoUpdate = false;
  if (displayNoUpdate) return;

  if (userGiftWager >= 200)
    $("#wagerGiftContainer").html(`<h5 class="t-grey">Your gift is ready !</h5>
    <div class="flex mt-2">
        <div class="button" onclick="openWagerGift()">Open</div>
    </div>`);
  else {
    $("#wagerGiftContainer").html(`<h5 class="t-grey">Wager <span
        class="t-orange">${200 - userGiftWager} satoshis</span>
    and claim a gift !</h5>
    <div class="progress mt-2">
    <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated w-100-p"
        role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
        style="width: ${(userGiftWager / 200) * 100}%">
    </div>
    </div>`);
  }

  $("#diceCashbackDisplay")
    .html(`<h5 class="t-grey">You have <span class="t-orange">${Math.floor(
    diceCashbackReward
  )} satoshis</span> bonus to claim.</h5>
    <div class="flex mt-2">
        ${
          diceCashbackReward >= 1
            ? `<div class="button" onclick="claimDiceCashback()">Claim</div>`
            : `<div class="btn btn-light disabled">Claim</div>`
        }
    </div>`);
}

// Open gift
function openWagerGift() {
  displayNoUpdate = true;
  $.ajax({
    type: "POST",
    url: "ajax/openWagerGift.php",
    success: (rewardMessage) => {
      $("#wagerGiftContainer").html(`<h5 class="t-grey">${rewardMessage}</h5>
          <div class="flex mt-2">
              <div class="button" onclick="updateWagerGift(true)">Great!</div>
          </div>`);
      userGiftWager = 0;
    },
  });
}

function claimDiceCashback() {
  displayNoUpdate = true;
  $.ajax({
    type: "POST",
    url: "ajax/getDiceCashback.php",
    success: (rewardMessage) => {
      increaseBalance(parseInt(rewardMessage));
      $("#diceCashbackDisplay")
        .html(`<h5 class="t-grey">You earned <span class="t-orange">${rewardMessage} satoshis !</span></h5>
          <div class="flex mt-2">
              <div class="button" onclick="updateWagerGift(true)">Great !</div>
          </div>`);
      diceCashbackReward = 0;
    },
  });
}

// Change Autobet color
function changeAutoBetColor(eventType, colorCode) {
  if (colorCode === rollOptions[eventType].changeBetColor) colorCode = false;
  rollOptions[eventType].changeBetColor = colorCode;

  let greenButton = document.getElementById(`${eventType}GreenOption`);
  let redButton = document.getElementById(`${eventType}RedOption`);
  let randomButton = document.getElementById(`${eventType}RandomOption`);

  if (colorCode === 0) {
    greenButton.classList.add("active");
    redButton.classList.remove("active");
    randomButton.classList.remove("active");
  } else if (colorCode === 1) {
    greenButton.classList.remove("active");
    redButton.classList.add("active");
    randomButton.classList.remove("active");
  } else if (colorCode === 2) {
    greenButton.classList.remove("active");
    redButton.classList.remove("active");
    randomButton.classList.add("active");
  } else {
    greenButton.classList.remove("active");
    redButton.classList.remove("active");
    randomButton.classList.remove("active");
  }
}

let betAmountInput = document.getElementById("betAmountInput");
function increaseBetAmount(betAmount, autoIncrease) {
  if (betAmount < 1) betAmount = 1;
  if (betAmount > userSatoshisAmount) {
    if (rollOptions.betAmount > betAmount) betAmount = userSatoshisAmount;
    return;
  }
  if (!autoIncrease) rollOptions.baseBetAmount = parseInt(betAmount);
  rollOptions.betAmount = parseInt(betAmount);
  betAmountInput.value = parseInt(betAmount);
}

setInterval(() => {
  if (
    rollOptions.lastRollWin == false &&
    Date.now() - rollOptions.lastRoll >= 10000
  ) {
    diceSubcontainer.hide(1000);
    rollOptions.rollNumber = 0;
    document
      .getElementById("rollDiceContainer")
      .classList.remove("no-bottom-radius");
  }
}, 3000);
