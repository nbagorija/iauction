<?php
include_once '../db.php';

header('Content-Type: application/json');

$login = $_POST['login'] ?? '';
$email = $_POST['email'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($login) || empty($email) || empty($username) || empty($password) || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[a-zA-Z0-9]+$/', $login)) {
    echo json_encode(['success' => false, 'error' => 'Некорректные данные.']);
    exit;
}

if (userExists($login)) {
    echo json_encode(['success' => false, 'error' => 'Пользователь с таким логином уже существует.']);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
addUser($login, $username, $email, $hashedPassword);

echo json_encode(['success' => true]);
