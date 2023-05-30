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
//$subscription = Subscription::create([
//    'endpoint' => 'https://fcm.googleapis.com/fcm/send/fWJWzP3soxk:APA91bFMYAEyc-_C4skiuvDpv0BS8jZaCDvAxvTHKlBaw_hDcJdSsyeQVR3RtQaLgWnIl4LdDDfKStFw8dP3Zd_Nbe5wlwIhaTFpDx7FKFtBoTMunQV1RzI5mMeVdSjcnXZ7Mp2VFOvY', // Firefox 43+,
//    'publicKey' => 'BAeq8zNfqnoJWF442FDOj0S6HzR7O4WUYmJN3sylL+Zw5G75jLU/IEE95xpgGacpHObDV9vcNItY3FKxSerw6Rc=', // base 64 encoded, should be 88 chars
//    'authToken' => '/e5WSLy9Mtcp/9RRrKJDnw==', // base 64 encoded, should be 24 chars
//]);

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
