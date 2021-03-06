<?php
    $originPath = "index";
    $relativePath = "index";
    $noAdblockCheck = true;

    include_once('src/components/header.php');

    $errorsText = [
        "short_email" => "Email is too short (min. 5 characters)",
        "short_pswd" => "Password is too short (min. 5 characters)",
        "long_pswd" => "Password is too long (max. 255 characters)",
        "long_email" => "Email is too long (max. 150 characters)",
        "short_username" => "Username is too short (min. 4 characters)",
        "long_username" => "Username is too long (max. 25 characters)",
        "username_taken" => "This username has already been used by an other account.",
        "email_taken" => "This email has already been used by an other account.",
        "captcha" => "Please complete the reCaptcha.",
        "email_notfound" => "We can't find an account with this email address.",
        "password" => "Incorrect password",
        "ip_exists" => "You already have an account on this IP address.",
        "banned" => "Looks like you have been banned from our website. Your ban expires in ".time_since($_GET["banTimestamp"]-time())
    ];
?>

<div class="flex page-center">
    <div class="col-lg-6 col-xs-11 fade-in">
        <?php if(($_GET["a"] ?? "") == "register") { ?>
        <h1 class="ml-3 mt-4">Register</h1>
        <div class="display-box bg-dark w-100p">
            <?php 
        // Checking for errors
        if(isset($_GET["e"])) {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Whoops!</strong>
                <?php echo ($errorsText[htmlspecialchars($_GET["e"])] ?? "An error occured..."); ?>.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php } ?>
            <form method="POST" action="client/login.php?a=register">
                <h5 class="mb-1 color-white bold">Username</h5>
                <input type="text" class="form-control" placeholder="Username" name="username" maxlength="25"
                    required></input>
                <h5 class="mb-1 mt-3 color-white bold">Email address</h5>
                <input type="email" class="form-control" placeholder="Email address" name="email" required
                    maxlength="150"></input>
                <h5 class="mb-1 mt-3 color-white bold">Password</h5>
                <input type="password" class="form-control" placeholder="Password" name="password" required
                    maxlength="255"></input>
                <h5 class="mb-1 mt-3 color-white bold">I am not a robot</h5>
                <div class="h-captcha mb-1 mt-1" data-sitekey="37de5ef0-4724-4dc7-9928-4e9572cf4f9f"></div>
                <p class="mt-1 t-grey">By clicking "Sign Up", you agree to our <a href="tos.html"
                        target="_blank">Terms</a> and you have
                    read our <a
                        href="https://www.cookiepolicygenerator.com/live.php?token=uEW57SGQ8jeDVsADgUfqPTo8g219HQtL"
                        target="_blank">Cookie Policy</a>.</p>
                <input type="submit" class="button mt-3" value="Sign Up"></input>
            </form>
        </div>
        <?php } else { ?>
        <h1 class="ml-3">Login</h1>
        <div class="display-box bg-dark w-100p">
            <?php 
        // Checking for errors
        if(isset($_GET["e"])) {
            ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Whoops!</strong>
                <?php echo ($errorsText[htmlspecialchars($_GET["e"])] ?? "An error occured..."); ?>.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <?php } ?>
            <form method="POST" action="client/login.php">
                <h5 class="mb-1 color-white bold">Email address</h5>
                <input type="email" class="form-control" placeholder="Email address" name="email" required></input>
                <h5 class="mb-1 mt-3 color-white bold">Password</h5>
                <input type="password" class="form-control" placeholder="Password" name="password" required></input>
                <h5 class="mb-1 mt-3 color-white bold">I am not a robot</h5>
                <div class="h-captcha mb-1 mt-1" data-sitekey="37de5ef0-4724-4dc7-9928-4e9572cf4f9f"></div>
                <input type="submit" class="button mt-3" value="Sign In"></input>
            </form>
        </div>
        <?php } ?>
    </div>
</div>

<?php
include_once('src/components/footer.php');
?>