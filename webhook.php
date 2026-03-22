<?php
require 'db.php';

$channelSecret     = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';
$channelAccessToken = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

// ตรวจสอบ signature
$body      = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_LINE_SIGNATURE'] ?? '';
$hash      = base64_encode(hash_hmac('sha256', $body, $channelSecret, true));

if (!hash_equals($hash, $signature)) {
    http_response_code(400);
    exit('Invalid signature');
}

$events = json_decode($body, true)['events'] ?? [];

foreach ($events as $event) {
    // รองรับเฉพาะ message ประเภท text
    if ($event['type'] !== 'message' || $event['message']['type'] !== 'text') {
        continue;
    }

    $replyToken = $event['replyToken'];
    $keyword    = trim($event['message']['text']);

    // ค้นหาใน DB
    $stmt = $pdo->prepare("SELECT answer FROM responses WHERE keyword = ? LIMIT 1");
    $stmt->execute([$keyword]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $replyText = $result
        ? $result['answer']
        : "ขออภัย ไม่พบคำตอบสำหรับ \"$keyword\"";

    // ส่งกลับหา LINE
    replyMessage($replyToken, $replyText, $channelAccessToken);
}

function replyMessage($replyToken, $text, $token) {
    $data = [
        'replyToken' => $replyToken,
        'messages'   => [['type' => 'text', 'text' => $text]]
    ];

    $ch = curl_init('https://api.line.me/v2/bot/message/reply');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ],
        CURLOPT_POSTFIELDS => json_encode($data)
    ]);
    curl_exec($ch);
    curl_close($ch);
}
