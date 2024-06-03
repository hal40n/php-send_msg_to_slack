<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
$token = $_ENV['SLACK_BOT_TOKEN'];
$channel = $_ENV['SLACK_CHANNEL'];

$message = 'Success!';

$data = [
    'channel' => $channel,
    'text' => $message,
];

$ch = curl_init('https://slack.com/api/chat.postMessage');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $token,
    'Content-Type: application/x-www-form-urlencoded'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$response_data = json_decode($response, true);

if ($httpcode === 200 && isset($response_data['ok']) && $response_data['ok']) {
    echo "Message sent to Slack!";
} else {
    $error_message = $response_data['error'] ?? 'Unknown error';
    echo "Failed to send message to Slack. HTTP Status Code: $httpcode. Error: $error_message\n";
}