<?php

require_once 'class/amoapi.php';
require_once 'class/contacts.php';
require_once 'class/leads.php';
require_once 'class/notes.php';
require_once 'class/tasks.php';

function loadIntegrationData() {
    if (file_exists('json/integration.json')) {
        return json_decode(file_get_contents('json/integration.json'), true);
    }
    return null;
}

function loadAuthToken() {
    if (file_exists('json/auth_data.json')) {
        return json_decode(file_get_contents('json/auth_data.json'), true);
    }
    return null;
}

function saveAccessToken($accessToken) {
    $tokenData = ['access_token' => $accessToken];
    file_put_contents('json/access_token.json', json_encode($tokenData, JSON_PRETTY_PRINT));
}

function getAccessToken() {
    if (file_exists('json/access_token.json')) {
        $data = json_decode(file_get_contents('json/access_token.json'), true);
        return $data['access_token'] ?? null;
    }
    return null;
}

function requestAccessToken($integrationData, $authData) {
    $data = [
        'client_id' => $integrationData['integration_id'],
        'client_secret' => $integrationData['client_secret'],
        'grant_type' => 'authorization_code',
        'code' => $authData['code'],
        'redirect_uri' => 'https://localhost/get_auth_data.php'
    ];
    
    $link = 'https://' . $authData['referer'] . '/oauth2/access_token';

    $curl = curl_init();
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
    curl_setopt($curl,CURLOPT_URL, $link);
    curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
    curl_setopt($curl,CURLOPT_HEADER, false);
    curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
    $out = curl_exec($curl);
    curl_close($curl);

    $responseData = json_decode($out, true);
    saveAccessToken($responseData['access_token']);

    return $responseData['access_token'];
}

function formHandler() {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $mail = $_POST['mail'];
    $city = $_POST['city'];
    $service = $_POST['service'];
    $comment = $_POST['comment'];

    // Загружаем данные авторизации и интеграции
    $integrationData = loadIntegrationData();
    $authData = loadAuthToken();
    $domain = $authData['referer'];
    $accessToken = getAccessToken();

    if (is_null($accessToken)) {
        // Нет токена, нужен новый
        $accessToken = requestAccessToken($integrationData, $authData);
    }

    $client = new AmoApi($accessToken, $domain);
    $contacts = new Contact($client);
    $leads = new Lead($client);
    $notes = new Note($client);
    $tasks = new Task($client);

    // Поиск дублей
    $double_id = $contacts->findDouble($phone, $mail);

    if ($double_id) {
        $tasks->createTask(-1, $double_id);  // Создаем задачу по повторной заявке
    } else {
        // Если дубль не найден, создаем новый контакт и сделку
        $contact_id = $contacts->createContact($name, $phone, $mail, $city);
        $lead_id = $leads->createLead($contact_id, $service);
        $tasks->createTask($lead_id, $contact_id);  // Создаем задачу
        $notes->createNote($lead_id, $comment);  // Добавляем заметку
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    formHandler();
}

?>