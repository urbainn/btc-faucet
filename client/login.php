<?php
    include_once("../src/config/pdo.php");
    include_once("../src/components/functions.php");

    $actionType = htmlspecialchars(($_GET["a"] ?? ""));
    $userEmail = htmlspecialchars($_POST["email"]);
    $userPassword = password_hash((htmlspecialchars($_POST["password"])), PASSWORD_BCRYPT);

    // Check captcha
    $data = array(
        'secret' => "" // Your hCaptcha secret here,
        'response' => $_POST['h-captcha-response']
    );
    $verify = curl_init();
    curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
    curl_setopt($verify, CURLOPT_POST, true);
    curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($verify);
    $responseData = json_decode($response);

    if(!$responseData->success) {
        header("Location:../login.php?a=$actionType&e=captcha");
        die;
    }

    if(strlen($userEmail) <= 4)
        header("Location:../login.php?a=$actionType&e=short_email");

    if(strlen(htmlspecialchars($_POST["password"])) <= 4)
        header("Location:../login.php?a=$actionType&e=short_pswd");

    if(strlen($userEmail) > 150)
        header("Location:../login.php?a=$actionType&e=long_email");

    if(strlen($userPassword) > 255)
        header("Location:../login.php?a=$actionType&e=long_pswd");

    if($actionType == "register") {

        $username = htmlspecialchars(($_POST["username"] ?? ""));
        
        if(strlen($username) < 4) {
            header("Location:../login.php?a=$actionType&e=short_username");
            die;
        }

        elseif(strlen($username) > 25) {
            header("Location:../login.php?a=$actionType&e=long_username");
            die;
        }

        // Check if username is already registered
        $checkUsername_stmt = $pdo->prepare("SELECT id FROM info_users WHERE username = :username");     
        $checkUsername_stmt->execute(array(':username' => $username));
        if($checkUsername_stmt->fetch())
            header("Location:../login.php?a=$actionType&e=username_taken");

        // Check if email is already used
        $checkEmail_stmt = $pdo->prepare("SELECT id FROM info_users WHERE email = :email");
        $checkEmail_stmt->bindParam(':email', $userEmail);
        $checkEmail_stmt->execute();

        if($checkEmail_stmt->fetch()) {
            header("Location:../login.php?a=$actionType&e=email_taken");
            die;
        }

        // Check if ip already used
        $checkIP_stmt = $pdo->prepare("SELECT id FROM info_users WHERE ipAddress = :ip");
        $checkIP_stmt->bindParam(':ip', $ip = $_SERVER['REMOTE_ADDR']);
        $checkIP_stmt->execute();

        if($checkIP_stmt->fetch()) {
            header("Location:../login.php?a=$actionType&e=ip_exists");
            die;
        }

        // Generate new session token
        $newSessionToken = substr(md5(microtime()),rand(0,26),90);
        $referralBase = "1";
        setcookie("sessionToken", $newSessionToken, time()+((3600*24)*16), "/");
        
        // Create account
        $createUserAccount_stmt = $pdo->prepare("INSERT INTO info_users (email, username, password, sessionToken, referralID, ipAddress) VALUES (:email, :username, :password, :sessionToken, :referralID, :ipAddress)");
        $createUserAccount_stmt->bindParam(':email', $userEmail);
        $createUserAccount_stmt->bindParam(':username', $username);
        $createUserAccount_stmt->bindParam(':password', $userPassword);
        $createUserAccount_stmt->bindParam(':sessionToken', $newSessionToken);
        $createUserAccount_stmt->bindParam(':ipAddress', $_SERVER['REMOTE_ADDR']);

        if(isset($_COOKIE["referralID"])) {
            $createUserAccount_stmt->bindParam(':referralID', $_COOKIE["referralID"]);
        } else {
            $createUserAccount_stmt->bindParam(':referralID', $referralBase);
        }

        $createUserAccount_stmt->execute();

        // Redirect to dashboard
        header("Location:dashboard.php");
        
    } else {

        // Check if email exists
        $checkEmail_stmt = $pdo->prepare("SELECT password,id,bannedTimestamp FROM info_users WHERE email = :email");     
        $checkEmail_stmt->execute(array(':email' => $userEmail));

        $emailUserInfos = $checkEmail_stmt->fetch();

        if(!$emailUserInfos)
            header("Location:../login.php?a=$actionType&e=email_notfound");
        
        elseif(!password_verify(htmlspecialchars($_POST["password"]), $emailUserInfos["password"])) {
            header("Location:../login.php?a=$actionType&e=password");
            die;
        }

        if($emailUserInfos["bannedTimestamp"] >= time()) {
            header("Location:../login.php?a=$actionType&e=banned&banTimestamp=".$emailUserInfos["bannedTimestamp"]);
            die;
        }

        // Generate new session token
        $newSessionToken = substr(md5(microtime()),rand(0,26),90);
        setcookie("sessionToken", $newSessionToken, time()+((3600*24)*16), "/");

        // Update account session token
        $pdo->query("UPDATE info_users SET sessionToken = \"$newSessionToken\", ipAddress = \"".$_SERVER['REMOTE_ADDR']."\" WHERE id = ".$emailUserInfos["id"]);

        // Redirect to dashboard
        header("Location:dashboard.php");

    }
?>