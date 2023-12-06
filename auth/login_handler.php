<?php
include_once '../db.php';
session_start();
header('Content-Type: application/json');

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($login) || empty($password) || !preg_match('/^[a-zA-Z0-9]+$/', $login)) {
    echo json_encode(['success' => false, 'error' => 'Неверные данные для входа.']);
    exit;
}

$user = getUserByLogin($login);

if (!$user || !password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'error' => 'Неправильный логин или пароль.']);
    exit;
}

$_SESSION['login'] = $user['login'];
$_SESSION['username'] = $user['username'];
$_SESSION['user_id'] = $user['id'];

echo json_encode(['success' => true]);
