<?php
$api_key = "4b3b890b3d2e4c06a892674f5660f7a6";
$base_url = "https://api.aimlapi.com/v1/chat/completions";
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $base_url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $api_key",
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode([
        "model" => "gpt-4o",
        "messages" => [
            ["role" => "user", "content" => "Test"]
        ]
    ])
]);
$response = curl_exec($curl);
if (curl_errno($curl)) {
    echo "Error: " . curl_error($curl);
} else {
    echo $response;
}
curl_close($curl);
?>