<?php

    // Finding relative path and page name
    $unmodifiedOriginPath = $originPath;
    $originPath = explode("/", ($originPath ?? "index"));
    $pageName = end($originPath);
    $pagePath = implode("/", $originPath);

    // Same thing with the "relative path"
    $relativePath = str_repeat("../", count(explode("/", ($relativePath ?? $pagePath))) - 1);

    // Create real paths
    $originPath = str_repeat("../", count($originPath) - 1);

    // PDO
    include_once($originPath."src/config/pdo.php");

    // Other files to include
    include_once($originPath."src/components/functions.php");

    // Get user infos
    $userInfos = getUserInfos($pdo);

    // Check if user is banned
    if($userInfos["bannedTimestamp"] > time()) {
        setCookie("sessionToken", "0", -1, "/");
        header("Location: ".$relativePath."index.php");
    }

    // Disconnect user session
    if(isset($_GET["revoke"])) {
        setcookie("sessionToken", "0", -1, "/");
        header("Location: ".$relativePath."index.php");
    }
?>

<!DOCTYPE html>
<html>

<!-- Head -->

<head>
    <!-- Meta Tags -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>[FAUCET NAME] Faucet » Earn, Multiply, Withdraw</title>
    <link rel="icon" type="image/png" href="https://i.imgur.com/aBqpetq.png?1" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">

    <!-- Style Sheets -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo $relativePath; ?>src/css/main.css">
    <link rel="stylesheet" href="<?php echo $relativePath; ?>src/css/<?php echo $unmodifiedOriginPath; ?>.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
        integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
        crossorigin="anonymous" />

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src='https://www.hCaptcha.com/1/api.js' async defer></script>

</head>

<body>
    <nav class="navbar header navbar-expand-lg navbar-dark bg-black">
        <a href="<?php echo $relativePath; ?>">
            <h3 class="t-white mr-05em"><i class="fab fa-bitcoin"></i> [FAUCET NAME] Faucet</h3>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php if($userInfos) { ?>
                <li class="nav-item">
                    <a class="nav-link color-white" href="<?php echo $relativePath; ?>client/dashboard.php"><i
                            class="fa fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link color-white" href="<?php echo $relativePath; ?>client/faucet.php"><i
                            class="fa fa-faucet"></i> Faucet</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle color-white" id="navbarDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fab fa-btc"></i> Earn
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="<?php echo $relativePath; ?>client/dice.php"><i
                                class="fa fa-dice mr-1"></i> Dice</a>
                        <a class="dropdown-item" href="<?php echo $relativePath; ?>client/referrals.php"><i
                                class="fa fa-users mr-1"></i> Referrals</a>
                    </div>
                </li>
                <?php } ?>
            </ul>
            <?php if(!$userInfos) { ?>
            <div class="flex gap-04em">
                <a href="<?php echo $relativePath; ?>login.php">
                    <div class="button">Login</div>
                </a>
                <a href="<?php echo $relativePath; ?>login.php?a=register">
                    <div class="button">Register</div>
                </a>
            </div>
            <?php } else { ?>
            <ul class="navbar-nav">
                <li class="nav-item flex align-center">
                    <div class="balance-display" id="btcBalance">
                        <?php echo convertToBTCFromSatoshi($userInfos["sats_balance"]); ?>
                        BTC
                    </div>
                </li>
                <script>
                let userSatoshisAmount = <?php echo $userInfos["sats_balance"]; ?>
                </script>
                <li class="nav-item dropdown">
                    <a class="nav-link color-white" id="profileDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">

                        <h5 class="color-white mr-2 t-white bold">
                            <?php echo htmlspecialchars($userInfos["username"]); ?> <i
                                class="fa fa-chevron-down t-grey"></i></h5>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="<?php echo $relativePath; ?>client/withdraw.php"><i
                                class="fa fa-wallet mr-1"></i> Withdraw</a>
                        <a class="dropdown-item" href="<?php echo $relativePath; ?>client/contact.php"><i
                                class="fa fa-poll mr-1"></i> Contact Us</a>
                        <?php if($userInfos["rank"] >= 70) { ?>
                        <a class="dropdown-item" href="<?php echo $relativePath; ?>client/admin/dashboard.php"><i
                                class="fa fa-cog mr-1"></i> Administration</a>
                        <?php } ?>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="?revoke"><i class="fa fa-sign-out-alt mr-1"></i> Log out</a>
                    </div>
                </li>
            </ul>
            <?php } ?>
        </div>
    </nav>

    <?php if(!isset($noAdblockCheck)) { ?>
    <div id="userInteraction">
        <div class="user-interaction-container">
            <div class="user-interaction col-lg-4 col-xs-12">
                <h2>AdBlock detected!</h2>
                <h5>Please disable your adblock and refresh this page to access [FAUCET NAME] Faucet. Ads are our only
                    source of
                    revenue, we wouldn't be able to pay our users otherwise!</h5>
            </div>
        </div>
    </div>
    <?php } ?>

    <link rel="stylesheet" type="text/css" href="https://cdn.wpcc.io/lib/1.0.2/cookieconsent.min.css" />
    <script src="https://cdn.wpcc.io/lib/1.0.2/cookieconsent.min.js" defer></script>
    <script src="<?php echo $relativePath; ?>client/adsbygoogle.js"></script>

    <script>
    if ((typeof allowUserInteraction == 'undefined')) {
        $("#userInteraction").show(500);
    }
    </script>