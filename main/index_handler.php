<?php
include_once '../db.php';

header('Content-Type: application/json');
updateLotStatus();
$lots = getLotsWithCurrentBids();
echo json_encode(['success' => true, 'data' => $lots]);
