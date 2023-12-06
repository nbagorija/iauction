<?php
include_once '../db.php';

header('Content-Type: application/json');
session_start();

$userInfo = getUserInfo($_SESSION['user_id']);
$wonLots = getWonLots($_SESSION['user_id']);

echo json_encode(['success' => true, 'data' => ['user' => $userInfo, 'wonLots' => $wonLots]]);
