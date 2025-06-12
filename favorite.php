<?php
session_start();
if (!isset($_SESSION['favorites'])) $_SESSION['favorites'] = [];
if (isset($_POST['store_id'])) {
    $store = [
        'id' => $_POST['store_id'],
        'name' => $_POST['store_name'],
        'address' => $_POST['store_address'],
        'lat' => $_POST['store_lat'],
        'lng' => $_POST['store_lng']
    ];
    // 重複登録防止
    $exists = false;
    foreach ($_SESSION['favorites'] as $fav) {
        if ($fav['id'] == $store['id']) {
            $exists = true;
            break;
        }
    }
    if (!$exists) {
        $_SESSION['favorites'][] = $store;
    }
}
header('Location: favorites.php');
exit;
