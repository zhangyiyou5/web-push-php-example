<?php
header('Content-Type: text/html; charset=utf-8');
$subscription = json_decode(file_get_contents('php://input'), true);

if (!isset($subscription['endpoint'])) {
    echo 'Error: not a subscription';
    return;
}

$method = $_SERVER['REQUEST_METHOD'];

var_dump($subscription);

//解码
$base64String = $subscription['publicKey'];
$binaryString = base64_decode($base64String);
$publicKey = unpack('C*', $binaryString);
$publicKeyString = implode('', array_map('chr', $publicKey));

echo "publicKey : {$publicKeyString}";

switch ($method) {
    case 'POST':
        // create a new subscription entry in your database (endpoint is unique)
        break;
    case 'PUT':
        // update the key and token of subscription corresponding to the endpoint
        // 需要存储的字段如下
        // 用户标识字段
        // 订阅端点字段（endpoint）
        // 公钥字段（publicKey）
        // 授权令牌字段（authToken）
        break;
    case 'DELETE':
        // delete the subscription corresponding to the endpoint
        break;
    default:
        echo "Error: method not handled";
        return;
}
