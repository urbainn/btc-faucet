<?php
function getUserInfos($pdo, $userID = false) {
    if($userID) {
        $userInfos = $pdo->query("SELECT * FROM info_users WHERE id = \"".$userID."\" LIMIT 0,1")->fetch();
    } else {
        $userInfos = $pdo->query("SELECT * FROM info_users WHERE sessionToken = \"".$_COOKIE["sessionToken"]."\" LIMIT 0,1")->fetch();
    }
    if(!$userInfos) return null;
    else return $userInfos;
}

function convertToBTCFromSatoshi($value){
    return sprintf('%.9f', floatval(intval($value)*0.000000001));;
}

function reduceNumber($value = 0, $reduceAt = 99999){
    $value = intval($value);
    if($value > $reduceAt) {
        return ($value < 1000000 ? ($value/1000000)+"M" : ($value/1000)+"k");       
    } else return $value;
}

function httpPostRequest($url, $data){

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, 
    http_build_query($data));

    // Receive server response ...
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);

    curl_close($ch);
    return json_decode($server_output);

}

// From: https://stackoverflow.com/questions/18685/how-to-display-12-minutes-ago-etc-in-a-php-webpage
function time_since($since) {
    $chunks = array(
        array(60 * 60 * 24 * 365 , 'year'),
        array(60 * 60 * 24 * 30 , 'month'),
        array(60 * 60 * 24 * 7, 'week'),
        array(60 * 60 * 24 , 'day'),
        array(60 * 60 , 'hour'),
        array(60 , 'minute'),
        array(1 , 'second')
    );

    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
        $seconds = $chunks[$i][0];
        $name = $chunks[$i][1];
        if (($count = floor($since / $seconds)) != 0) {
            break;
        }
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";
    return $print;
}

/* Display an advert
 @param string $adType - Type of ad to display (banner, square, long-banner)
 @param int $levelRequirement = 10 - Display the ad if the user has achieved this level
 */

function displayAdvert($adType, $levelRequirement = 10, $userLevel = 0) {
    // IMPORTANT: Replace these with your own ad blocks, these our old ad config:
    // Some good ad networks: bitmedia.io, aads and i forgot the name of the other ones :')
    if($userLevel >= $levelRequirement) {
        if($adType == 'banner') {

            // 728 x 90 (banners)
            $advertCodes = [
                '<ins class="5e6e49d67f1e6d02ff52d979" style="display:inline-block;width:728px;height:90px;"></ins><script>!function(e,n,c,t,o,r){!function e(n,c,t,o,r,m,s,a){s=c.getElementsByTagName(t)[0],(a=c.createElement(t)).async=!0,a.src="https://"+r[m]+"/js/"+o+".js",a.onerror=function(){a.remove(),(m+=1)>=r.length||e(n,c,t,o,r,m)},s.parentNode.insertBefore(a,s)}(window,document,"script","5e6e49d67f1e6d02ff52d979",["cdn.bmcdn1.com"],0)}();</script>',
                '<ins class="5e6e49d67f1e6d02ff52d979" style="display:inline-block;width:728px;height:90px;"></ins><script>!function(e,n,c,t,o,r){!function e(n,c,t,o,r,m,s,a){s=c.getElementsByTagName(t)[0],(a=c.createElement(t)).async=!0,a.src="https://"+r[m]+"/js/"+o+".js",a.onerror=function(){a.remove(),(m+=1)>=r.length||e(n,c,t,o,r,m)},s.parentNode.insertBefore(a,s)}(window,document,"script","5e6e49d67f1e6d02ff52d979",["cdn.bmcdn1.com"],0)}();</script>',
                '<ins class="5e6e49d67f1e6d02ff52d979" style="display:inline-block;width:728px;height:90px;"></ins><script>!function(e,n,c,t,o,r){!function e(n,c,t,o,r,m,s,a){s=c.getElementsByTagName(t)[0],(a=c.createElement(t)).async=!0,a.src="https://"+r[m]+"/js/"+o+".js",a.onerror=function(){a.remove(),(m+=1)>=r.length||e(n,c,t,o,r,m)},s.parentNode.insertBefore(a,s)}(window,document,"script","5e6e49d67f1e6d02ff52d979",["cdn.bmcdn1.com"],0)}();</script>',
                '<script data-cfasync="false" type="text/javascript" src="//adconity.com/display/items.php?8824&17496&728&90&4&0&0"></script>',
                '<iframe src="http://coinmedia.co/new_code_site133058.js" scrolling="no" frameborder="0" width="728px" height="120px"></iframe>',
                '<div id="data_50796"></div><script data-cfasync="false" async type="text/javascript" src="//www.bitcoadz.io/display/items.php?50796&78244&728&90&4&0&0&0&0"></script>'
            ];

            echo $advertCodes[rand(0, count($advertCodes)-1)];
            
        } elseif($adType == "square") {

            // 250 x 250 (square)
            $advertCodes = [
                '<ins class="6031d26f0d27013e5bb657cf" style="display:inline-block;width:250px;height:250px;"></ins>
                <script>!function(e,n,c,t,o,r){!function e(n,c,t,o,r,m,s,a){s=c.getElementsByTagName(t)[0],(a=c.createElement(t)).async=!0,a.src="https://"+r[m]+"/js/"+o+".js",a.onerror=function(){a.remove(),(m+=1)>=r.length||e(n,c,t,o,r,m)},s.parentNode.insertBefore(a,s)}(window,document,"script","6031d26f0d27013e5bb657cf",["cdn.bmcdn1.com"],0)}();</script>',
                '<script data-cfasync="false" type="text/javascript" src="//adconity.com/display/items.php?8825&17496&250&250&4&0&11"></script>',
                '<iframe data-aa="1576357" src="//ad.a-ads.com/1576357?size=250x250" scrolling="no" style="width:250px; height:250px; border:0px; padding:0; overflow:hidden" allowtransparency="true"></iframe>',
                '<div id="data_50840"></div><script data-cfasync="false" async type="text/javascript" src="//www.bitcoadz.io/display/items.php?50840&78244&250&250&4&0&0&0&0"></script>'
            ];

            echo $advertCodes[rand(0, count($advertCodes)-1)];

        } elseif($adType == "long-banner") {

            // 160 x 600 (long banner, height banners)
            $advertCodes = [
                '<ins class="603274340d27019b45b6580b" style="display:inline-block;width:160px;height:600px;"></ins>
                <script>!function(e,n,c,t,o,r){!function e(n,c,t,o,r,m,s,a){s=c.getElementsByTagName(t)[0],(a=c.createElement(t)).async=!0,a.src="https://"+r[m]+"/js/"+o+".js",a.onerror=function(){a.remove(),(m+=1)>=r.length||e(n,c,t,o,r,m)},s.parentNode.insertBefore(a,s)}(window,document,"script","603274340d27019b45b6580b",["cdn.bmcdn1.com"],0)}();</script>',
                '<div id="data_50838"></div><script data-cfasync="false" async type="text/javascript" src="//www.bitcoadz.io/display/items.php?50838&78244&160&600&4&0&0&0&0"></script>'
            ];

            echo $advertCodes[rand(0, count($advertCodes)-1)];

        } elseif($adType == "rectangle") {

            // 468 x 60 (rectangle ads)
            $advertCodes = [
                '<ins class="6033bf5de663c1df677b61a0" style="display:inline-block;width:468px;height:60px;"></ins>
                <script>!function(e,n,c,t,o,r){!function e(n,c,t,o,r,m,s,a){s=c.getElementsByTagName(t)[0],(a=c.createElement(t)).async=!0,a.src="https://"+r[m]+"/js/"+o+".js",a.onerror=function(){a.remove(),(m+=1)>=r.length||e(n,c,t,o,r,m)},s.parentNode.insertBefore(a,s)}(window,document,"script","6033bf5de663c1df677b61a0",["cdn.bmcdn1.com"],0)}();</script>',
                '<div id="data_50841"></div><script data-cfasync="false" async type="text/javascript" src="//www.bitcoadz.io/display/items.php?50841&78244&468&60&4&0&0&0&0"></script>'
            ];

            echo $advertCodes[rand(0, count($advertCodes)-1)];

        }
    }
}

function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

?>