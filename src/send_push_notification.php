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
$subscription = [
    'endpoint' => 'https://wns2-sg2p.notify.windows.com/w/?token=BQYAAABfnVLh93DC2ia5KqE9lX9mU1Chq4Pd%2fjIn1yx2Kc1oYEG67mthZrGT9kTNZr4v5etgZ5bh6Gue2HSJ176Pez%2b3byPI6ElTiwdF%2b6MJrZyCYX5LIWqp%2fHxzrZaswBmn20EkQyVYXs84trDuqOuCx%2bKj3g6n5IeNV%2fGKwO%2f0RgBKEZWqG3uprtfKoE8SuljpN2dDkjaul87ubT6PRogN8aOlALt8Xg2DSS10HBoFljfL3Ha6SY%2b%2bSmgBjKNwovsRaKrNOZkMja9nQhlccPBlfZhrezI1eqKf6jdkMv6TvJ3ij3l7UzCWugfJ1lNSmP2%2f4LLniqs1qGMTkQ5AhVP46Me%2f',
    'authToken' => 'yGqLq9dHidGNzM4ugQx2yw==',
    'publicKey' => 'BBqxZcA4p4lznAR7KG0J207rVdupSvb3eXpum+ns4zevFAMr9GaITuyFq4YIqo+oe6jgiionVRfQ0LYd9NdjlMo=',
];


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
