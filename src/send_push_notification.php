<?php
require __DIR__ . '/../vendor/autoload.php';
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\VAPID;


$subscription = Subscription::create(json_decode(file_get_contents('php://input'), true));

var_dump($subscription);

$auth = array(
    'VAPID' => array(
        'subject' => 'https://yigen.powerbuyin.top',
        'publicKey' => file_get_contents(__DIR__ . '/../keys/public_key.txt'), // don't forget that your public key also lives in app.js
        'privateKey' => file_get_contents(__DIR__ . '/../keys/private_key.txt'), // in the real world, this would be in a secret file
    ),
);

$webPush = new WebPush($auth);

//参数从数据库获取
$subscription = Subscription::create([
    'endpoint' => 'https://fcm.googleapis.com/fcm/send/d0TH_3q920U:APA91bEXaWgUZx7VV7SZKM4XUua_sTp9FrY6uhhPUgOoCiQ_zfyCP2IlhGcV0cODj06e5i0BP599Yb9m7gkxEYJCh9TPvqd5Wbv1Ad1qjdvwf4bf96FwgwRY5cb9s5eLjwnLERItMRs0', // Firefox 43+,
    'publicKey' => 'BMBsQg3PP56yG6eqIAIf5rfrwKxGsRbwvy0fG1cUTyhvHWl+4QMI260FO72z4MkH8m+iV9oyK+O8x+bIWSGowl0=', // base 64 encoded, should be 88 chars
    'authToken' => 'nCcpo8Bg8fLVVFtRs88GuA==', // base 64 encoded, should be 24 chars
]);

//var_dump($subscription);

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
