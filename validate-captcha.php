 <?php

header("Content-Type: application/json");

$secret = "6LdticksAAAAAHIegpwpH69Zks_miOR4_ZlG43Q4";
$responseToken = $_POST['g-recaptcha-response'] ?? '';

if (!$responseToken) {
    echo json_encode(array("success" => false, "message" => "Token requerido"));
    exit;
}

// Configurar la petición POST para Google
$postdata = http_build_query([
    'secret'   => $secret,
    'response' => $responseToken,
    'remoteip' => $_SERVER['REMOTE_ADDR']
]);

$opts = array(
    'http' => array(
        'method'  => 'POST',
        'header'  => 'Content-type: application/x-www-form-urlencoded',
        'content' => $postdata
    )
);

$context = stream_context_create($opts);

// Validar contra Google
$verify = file_get_contents(
    "https://www.google.com/recaptcha/api/siteverify",
    false,
    $context
);

$captcha  = json_decode($verify);
$succes   = $captcha->success;
echo json_encode(array("success" => true));
