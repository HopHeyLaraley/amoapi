<?php

// Проверка наличия кода авторизации в запросе
if (isset($_GET['code'])) {
    $authData = [
        'code' => $_GET['code'],
        'state' => $_GET['state'],
        'referer' => $_GET['referer'],
        'platform' => $_GET['platform'],
        'client_id' => $_GET['client_id']
    ];

    // Сохранение данных авторизации в файл
    file_put_contents('json/auth_data.json', json_encode($authData, JSON_PRETTY_PRINT));

    // Переадресация пользователя на главную страницу
    header('Location: https://localhost/');
} else {
    // Если код не был передан
    echo "Ошибка: отсутствуют данные для авторизации.";
    exit();
}
