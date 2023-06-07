<?php
require __DIR__ . '/../vendor/autoload.php';
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\VAPID;


//$subscription = Subscription::create(json_decode(file_get_contents('php://input'), true));
//var_dump($subscription);

$auth = array(
    'VAPID' => array(
        'subject' => 'https://baidu.top/',
        'publicKey' => file_get_contents(__DIR__ . '/../keys/public_key.txt'), // don't forget that your public key also lives in app.js
        'privateKey' => file_get_contents(__DIR__ . '/../keys/private_key.txt'), // in the real world, this would be in a secret file
    ),
);

$webPush = new WebPush($auth);

//参数从数据库获取
$subscription = Subscription::create([
    'endpoint' => 'https://fcm.googleapis.com/fcm/send/d06cWYtZLL0:APA91bH9EljjzSwKylSdwz4F5VPVw-JwHFB9KpP-pV9TD419tKlpr1lRx-ZoqqmqA3pYsen8YX7KE7Qxc_iT8_kehTO2GrJNqCNaVEl12IYh7euNAW9lvxv_-2maYrxJNUYXhXGduab7', // Firefox 43+,
    'publicKey' => 'BP+/KZygGBY11YEjZzY4uvNMfbG8KCzB+XiQLLLEaRmelJHqmEMd49l250M5+LVqI6zSmUaNqVVMrn7qs9b5JRY=', // base 64 encoded, should be 88 chars
    'authToken' => 'weVd2PFvuwGhRgiNaFBarA==', // base 64 encoded, should be 24 chars
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




