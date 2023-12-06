<?php
include_once '../db.php';

header('Content-Type: application/json');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_FILES['image']) || $_FILES['image']['error'] != UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'error' => 'Ошибка загрузки файла']);
        exit;
    }
    $image = $_FILES['image'];


    $validExtensions = ['jpg', 'jpeg', 'png'];
    $fileExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $validExtensions)) {
        echo json_encode(['success' => false, 'error' => 'Неверный формат файла']);
        exit;
    }


    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $startPrice = $_POST['start_price'] ?? 0;
    $endTime = $_POST['end_time'] ?? '';
    $formattedEndTime = date('Y-m-d H:i:s', strtotime($endTime));
    $latitude = $_POST['latitude'] ?? 0;
    $longitude = $_POST['longitude'] ?? 0;

    if ($startPrice <= 0 || empty($title) || empty($description) || strtotime($endTime) <= time()) {
        echo json_encode(['success' => false, 'error' => 'Неверные данные лота']);
        exit;
    }


    $uploadDir = '../uploads/';
    $uploadFilePath = $uploadDir . basename($image['name']);
    if (!move_uploaded_file($image['tmp_name'], $uploadFilePath)) {
        echo json_encode(['success' => false, 'error' => 'Ошибка при сохранении файла']);
        exit;
    }


    addLot($title, $description, $uploadFilePath, $startPrice, $formattedEndTime, $latitude, $longitude);
    echo json_encode(['success' => true]);
}
