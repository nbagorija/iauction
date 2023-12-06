<?php
include_once '../db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    echo json_encode(['success' => true, 'data' => getAllLots()]);
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    $lotId = $_POST['lotId'] ?? '';

    if ($action == 'delete') {
        deleteLot($lotId);
        echo json_encode(['success' => true]);
    } elseif ($action == 'toggleActive') {
        $isActive = $_POST['isActive'] ?? '';

        $lot = getLotById($lotId);
        if (strtotime($lot["end_time"]) < time() || $lot["user_id"] != null) {
            echo json_encode(['success' => false, 'error' => 'Невозможно логически изменить статус лота']);
        } else {
            toggleLotActive($lotId, $isActive);
            echo json_encode(['success' => true]);
        }
    }
}
