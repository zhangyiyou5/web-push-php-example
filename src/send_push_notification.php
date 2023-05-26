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
//$subscription = [
//    'endpoint' => '需要发给谁，从数据库获取',
//    'keys' => [
//        'p256dh' => '从数据库获取',
//        'auth' => '从数据库获取',
//    ],
//];

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
