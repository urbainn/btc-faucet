# BTC-faucet
Bitcoin faucet full CMS (also known as "Waddow Faucet")

## Features
- Faucet
- Referrals
- Withdraw to faucetpay.io
- Administration (admin dashboard)
- Advanced Support Ticket system
- Bonuses, multipliers
- Leveling system (w/ bonuses)
- Dice Game
- Auto Dice game (with an advanced config)
- Secured

## How does it look?
![Front page](https://i.imgur.com/0hJ7woN.png)
![The Dice](https://i.imgur.com/1IYIkbY.png)
![Dashboard](https://i.imgur.com/7W1tvc1.png)

## Configuration
Making the website work is pretty simple, you just need a databse, where you import the `database.sql` file, and you need to link the database to the website in the `src/config/pdo.php` file, for example:
```php
/* Connexion à une base MySQL avec l'invocation de pilote */
$dsn = 'host=localhost;dbname=test';
$user = 'root';
$password = '';
```

And it should work! It's that easy, but now you need to configure the captcha, and it also is really easy! Just go to https://hcaptcha.com, create a new website and take get secret key, you now need to paste it in these files: `client\login.php` and `client\ajax\claimFaucet.php` 
It should look something like that:
```php
    // Check captcha
    $data = array(
        'secret' => "YOUR hCAPTAHCA SECRET KEY" // Your hCaptcha secret here,
        'response' => $_POST['h-captcha-response']
    );
```

Now the captcha work, so you can register and claim the faucet.. but for the website to work at 100%, you need to create a faucethub.io faucet, just go to https://faucethub.io, then go to "Faucet Owner Dashboard", create a new faucet and get your secret key, then, just past it multiple times in this file: `src\components\faucethub.php` like this:
```php
    curl_setopt($ch, CURLOPT_URL,"https://faucetpay.io/api/v1/balance");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "api_key=YOUR_API_KEY&currency=$currency");
```
(NOTE: you have to do this 3 times, in the 3 functions, just replace every "YOUR_API_KEY" with your secret faucethub.io API key).
