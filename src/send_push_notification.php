<?php
require __DIR__ . '/../vendor/autoload.php';
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\VAPID;


$subscription = Subscription::create(json_decode(file_get_contents('php://input'), true));

var_dump($subscription);

$auth = array(
    'VAPID' => array(
        'subject' => 'https://webpush.powerbuyin.top/',
        'publicKey' => file_get_contents(__DIR__ . '/../keys/public_key.txt'), // don't forget that your public key also lives in app.js
        'privateKey' => file_get_contents(__DIR__ . '/../keys/private_key.txt'), // in the real world, this would be in a secret file
    ),
);

$webPush = new WebPush($auth);


//参数从数据库获取
$subscription = Subscription::create([
    'endpoint' => 'https://wns2-sg2p.notify.windows.com/w/?token=BQYAAADxkCVKfKtZ1ln2PeXk6vDKp9nsejGN1C37PpCEy1hQFCxULzbQsX%2bSx9gvcDA7TW2TlzrHcmTKom49nChXcR22qXLDNO%2boJGNQ1G8Tk%2bd1ucVNZuyJ4%2f57EEW4ojZLtphGX%2bkkTehykOLtbGbMztg0kMXsKURGIzh4wfvP5s7Ct%2flC%2bs5zQeyqX79itYI%2fgstREmgqpGDk88majH%2fhTVQ3soeL%2fmE%2fha8X%2bCXwiyzlwunotrPc9Yu8vwLo6swzMunpxZjG9WQWdPyD1JSERsz%2bqdIbwJqEO6G3GQjLj3GJvyQ3ezCDPGiPv%2flXqNWb6iJA%2bHB3Z6a48XVbcJL5edyG', // Firefox 43+,
    'publicKey' => 'BMyfFYJa07bqpqPAKMoGusVGX+hOEu2bo2TW6mpkv5WARMF4Ey90RZy1MW+XuUoaXc0RwA3izg/PTdMiZGTtw1I=', // base 64 encoded, should be 88 chars
    'authToken' => 'UOH0QSWJIAwq8eT/b8LorA==', // base 64 encoded, should be 24 chars
]);

var_dump($subscription);

$report = $webPush->sendOneNotification(
    $subscription,
    '{"title":"Hello World!","body":"body","icon":"icon"}'
);

// handle eventual errors here, and remove the subscription from your server if it is expired
$endpoint = $report->getRequest()->getUri()->__toString();

if ($report->isSuccess()) {
    echo "[v] Message sent successfully for subscription {$endpoint}.";
} else {
    echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
}
