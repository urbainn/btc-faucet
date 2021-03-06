<?php
    $originPath = "index";
    $noAdblockCheck = true;
    
    if(isset($_GET["r"])) setcookie("referralID", htmlspecialchars($_GET["r"]), time()+7200, "/");

    include_once('src/components/header.php');
?>

<div class="w-100-p color-container bg-dark mobile-wrap relative overflow-hidden">
    <div class="bubble bubble-1"></div>
    <div class="bubble bubble-2"></div>
    <img src="https://i.imgur.com/5ugjejl.png" class="col-lg-4 col-xs-12">
    <div>
        <h1 class="t-white large-title">Win big</h1>
        <h4 class="t-grey">Win up to <span class="t-orange">300 000</span> satoshis every 10 minutes !</h4>
        <h4 class="t-grey">Register and get <span class="t-orange">100</span> sats. bonus !</h4>
    </div>
</div>

<svg xmlns="http://www.w3.org/2000/svg" class="wave" viewBox="0 0 1440 320">
    <path fill="#343a40" fill-opacity="1"
        d="M0,160L48,138.7C96,117,192,75,288,90.7C384,107,480,181,576,224C672,267,768,277,864,245.3C960,213,1056,139,1152,117.3C1248,96,1344,128,1392,144L1440,160L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
    </path>
</svg>

<div class="responsive-container">
    <div class="flex center gap-1em wrap">
        <div class="col-lg-9 col-xs-12">
            <!-- Illustr boxs -->
            <div class="flex gap-1em mobile-wrap mobile-no-gap">
                <div class="illustr-box bg-dark col-lg-6 col-xs-12">
                    <div class="illustr">
                        <i class="fa fa-faucet"></i>
                    </div>
                    <div>
                        <h3 class="t-white">Faucet</h3>
                        <h5 class="t-grey">Claim up to 300k satoshis every 10 minutes</h5>
                    </div>
                </div>
                <div class="illustr-box bg-dark col-lg-6 col-xs-12">
                    <div class="illustr">
                        <i class="fa fa-dice"></i>
                    </div>
                    <div>
                        <h3 class="t-white">Multiply</h3>
                        <h5 class="t-grey">Multiply your earnings by playing dice</h5>
                    </div>
                </div>
            </div>
            <div class="flex gap-1em mobile-wrap mobile-no-gap">
                <div class="illustr-box bg-dark col-lg-6 col-xs-12">
                    <div class="illustr">
                        <i class="fa fa-calendar-check"></i>
                    </div>
                    <div>
                        <h3 class="t-white">Daily Bonuses</h3>
                        <h5 class="t-grey">Claim bonuses every day and complete tasks to get extra rewards !</h5>
                    </div>
                </div>
                <div class="illustr-box bg-dark col-lg-6 col-xs-12">
                    <div class="illustr">
                        <i class="fa fa-magic"></i>
                    </div>
                    <div>
                        <h3 class="t-white">Auto Faucet</h3>
                        <h5 class="t-grey">Keep your browser open and earn bitcoins</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(isset($_GET["ads"])) { ?>
<div class="flex center gap-1em mobile-wrap w-100-p mt-3">
    <iframe data-aa="1576357" src="//ad.a-ads.com/1576357?size=250x250" scrolling="no"
        style="width:250px; height:250px; border:0px; padding:0; overflow:hidden" allowtransparency="true"></iframe>
</div>
<iframe data-aa="1576739" src="//ad.a-ads.com/1576739?size=468x60" scrolling="no"
    style="width:468px; height:60px; border:0px; padding:0; overflow:hidden" allowtransparency="true"></iframe>
<iframe data-aa="1577637" src="//ad.a-ads.com/1577637?size=160x600" scrolling="no"
    style="width:160px; height:600px; border:0px; padding:0; overflow:hidden" allowtransparency="true"></iframe>
<?php } ?>

<?php
include_once('src/components/footer.php');
?>