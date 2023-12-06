<?php
include_once '../db.php';

header('Content-Type: application/json');
session_start();

$userId = $_SESSION['user_id'];

$lotId = $_POST['lotId'] ?? '';
$bidAmount = $_POST['bidAmount'] ?? '';
updateLotStatus();
$lotDetails = getLotDetails($lotId, $userId);

if ($bidAmount) {
    if ($bidAmount <= $lotDetails["start_price"] || $bidAmount <= $lotDetails['currentBid'] || !$lotDetails['is_active']) {
        echo json_encode(['success' => false, 'error' => 'Ставка недействительна.']);
        exit;
    }

    addBid($lotId, $userId, $bidAmount);
    echo json_encode(['success' => true]);
    exit;
}

echo json_encode(['success' => true, 'data' => $lotDetails]);
