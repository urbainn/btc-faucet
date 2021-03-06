let allowUserInteraction = true;

var adBlockEnabled = false;
var testAd = document.createElement("div");
testAd.innerHTML = "&nbsp;";
testAd.className = "adsbox";
document.body.appendChild(testAd);
window.setTimeout(function () {
  if (testAd.offsetHeight === 0) {
    adBlockEnabled = true;
  }
  testAd.remove();
  if (adBlockEnabled) {
    $("#userInteraction").show(500);
  }
}, 100);

window.addEventListener("load", function () {
  window.wpcc.init({
    border: "thick",
    corners: "large",
    colors: {
      popup: {
        background: "#222222",
        text: "#ffffff",
        border: "#fde296",
      },
      button: {
        background: "#fde296",
        text: "#000000",
      },
    },
    position: "bottom",
    content: {
      href:
        "https://www.cookiepolicygenerator.com/live.php?token=uEW57SGQ8jeDVsADgUfqPTo8g219HQtL",
    },
  });
});

function increaseBalance(satsAmount) {
  $("#btcBalance").html(
    (
      (parseInt(userSatoshisAmount) + parseInt(satsAmount)) *
      0.00000001
    ).toFixed(8) + " BTC"
  );
  userSatoshisAmount += satsAmount;
}
