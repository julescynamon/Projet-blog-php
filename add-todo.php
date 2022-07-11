<?php

$_POST = filter_input_array(
    INPUT_POST,
    FILTER_SANITIZE_FULL_SPECIAL_CHARS
);
$todo = $_POST['todo'] ?? '';

if (!$todo) {
    $error = ERROR_REQUIRED;
} else if (mb_strlen($todo) < 5) {
    $error = ERROR_TOO_SHORT;
} else if (mb_strlen($todo) > 200) {
    $error = ERROR_TOO_LONG;
} else if (array_search(mb_strtolower($todo), array_map(fn ($el) => mb_strtolower($el['name']), $todos))) {
    $error = ERROR_ALREADY_EXISTS;
}

if (!$error) {
    $todos[] = [
        'id' => time(),
        'name' => $todo,
        'done' => false,
    ];
    file_put_contents($filename, json_encode($todos));
    header('Location: /');
}
