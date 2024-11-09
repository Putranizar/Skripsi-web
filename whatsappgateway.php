<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $phone = $_POST['phone'];
    $message = $_POST['message'];

    $data = [
        'phone' => $phone,
        'message' => $message
    ];

    $ch = curl_init('https://api.whatsapp-gateway.com/send');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
}
?>
