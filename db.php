<?php

// Функция подключения к базе данных
function connectDB()
{
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "iauction";

    $conn = new mysqli($hostname, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}

// Функция для получения пользователя по логину
function getUserByLogin($login)
{
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT * FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $user;
}

function getUserInfo($userId)
{
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT login, username, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userInfo = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $userInfo;
}

function getWonLots($userId)
{
    $conn = connectDB();
    // Запрос для получения лотов, выигранных пользователем, и их победных ставок
    $sql = "SELECT l.*, b.amount AS bid
            FROM lots l
            JOIN bids b ON l.id = b.lot_id
            WHERE l.user_id = ? AND l.is_active = 0
            AND b.amount = (
                SELECT MAX(b2.amount)
                FROM bids b2
                WHERE b2.lot_id = l.id
            )";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $wonLots = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $wonLots;
}


// Функция для проверки существования пользователя по логину
function userExists($login)
{
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT id FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    $conn->close();
    return $exists;
}

// Функция для добавления нового пользователя
function addUser($login, $username, $email, $password, $is_admin = 0)
{
    $conn = connectDB();
    $stmt = $conn->prepare("INSERT INTO users (login, username, email, password, is_admin) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $login, $username, $email, $password, $is_admin);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

// Функция для проверки, является ли пользователь администратором
function isAdmin($userId)
{
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        return (bool)$row['is_admin'];
    }
    return false;
}

// Функция для получения всех активных лотов
function getActiveLots()
{
    $conn = connectDB();
    $result = $conn->query("SELECT * FROM lots WHERE is_active = 1");
    $lots = $result->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $lots;
}

// Функция для получения всех лотов
function getAllLots()
{
    $conn = connectDB();
    $result = $conn->query("SELECT * FROM lots");
    $lots = $result->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $lots;
}

// Функция для получения деталей лота по ID
function getLotById($lotId)
{
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT * FROM lots WHERE id = ?");
    $stmt->bind_param("i", $lotId);
    $stmt->execute();
    $result = $stmt->get_result();
    $lot = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
    return $lot;
}

// Функция для получения лотов их максимальной ставки
function getLotsWithCurrentBids()
{
    $conn = connectDB();
    $sql = "SELECT l.id, l.title, l.image, l.end_time, l.start_price, MAX(b.amount) AS currentBid
            FROM lots l
            LEFT JOIN bids b ON l.id = b.lot_id
            WHERE l.is_active = 1
            GROUP BY l.id";
    $result = $conn->query($sql);
    $lots = $result->fetch_all(MYSQLI_ASSOC);
    $conn->close();
    return $lots;
}

// Функция для получения лота и его максимальной ставки
function getLotDetails($lotId, $userId)
{
    $conn = connectDB();

    // Получаем данные лота и текущую максимальную ставку
    $sql = "SELECT l.*, MAX(b.amount) as currentBid, l.latitude, l.longitude
            FROM lots l
            LEFT JOIN bids b ON l.id = b.lot_id
            WHERE l.id = ?
            GROUP BY l.id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $lotId);
    $stmt->execute();
    $result = $stmt->get_result();
    $lot = $result->fetch_assoc();

    // Получаем максимальную ставку текущего пользователя по этому лоту
    $sqlUserBid = "SELECT MAX(amount) as userBid FROM bids WHERE user_id = ? AND lot_id = ?";
    $stmtUserBid = $conn->prepare($sqlUserBid);
    $stmtUserBid->bind_param("ii", $userId, $lotId);
    $stmtUserBid->execute();
    $resultUserBid = $stmtUserBid->get_result();
    $userBid = $resultUserBid->fetch_assoc();

    $lot['userBid'] = $userBid['userBid'];

    $stmt->close();
    $stmtUserBid->close();
    $conn->close();

    return $lot;
}

// Функция для добавления новой ставки
function addBid($lotId, $userId, $amount)
{
    $conn = connectDB();
    $stmt = $conn->prepare("INSERT INTO bids (lot_id, user_id, amount) VALUES (?, ?, ?)");
    $stmt->bind_param("iid", $lotId, $userId, $amount);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

// Функция для получения ставок по лоту
function getBidsByLotId($lotId)
{
    $conn = connectDB();
    $stmt = $conn->prepare("SELECT * FROM bids WHERE lot_id = ?");
    $stmt->bind_param("i", $lotId);
    $stmt->execute();
    $result = $stmt->get_result();
    $bids = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
    return $bids;
}

function updateLotStatus()
{
    $conn = connectDB();

    // Находим лоты, которые должны быть закрыты
    $sqlSelect = "SELECT id FROM lots WHERE end_time < NOW() AND is_active = 1";
    $result = $conn->query($sqlSelect);
    $lotsToClose = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($lotsToClose as $lot) {
        $lotId = $lot['id'];

        // Определяем победителя на основе максимальной ставки
        $sqlWinner = "SELECT user_id FROM bids WHERE lot_id = ? ORDER BY amount DESC LIMIT 1";
        $stmt = $conn->prepare($sqlWinner);
        $stmt->bind_param("i", $lotId);
        $stmt->execute();
        $winnerResult = $stmt->get_result();
        $winner = $winnerResult->fetch_assoc();
        $winnerUserId = $winner ? $winner['user_id'] : NULL;

        // Обновляем статус лота и устанавливаем победителя
        $sqlUpdate = "UPDATE lots SET is_active = 0, user_id = ? WHERE id = ?";
        $updateStmt = $conn->prepare($sqlUpdate);
        $updateStmt->bind_param("ii", $winnerUserId, $lotId);
        $updateStmt->execute();
        $updateStmt->close();
    }

    $conn->close();
}

function deleteLot($lotId)
{
    $conn = connectDB();
    $sql = "DELETE FROM lots WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $lotId);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

function toggleLotActive($lotId, $isActive)
{
    $conn = connectDB();

    // Если деактивируем лот, назначаем победителя
    if ($isActive == 0) {
        // Находим победителя на основе максимальной ставки
        $sqlWinner = "SELECT user_id FROM bids WHERE lot_id = ? ORDER BY amount DESC LIMIT 1";
        $stmtWinner = $conn->prepare($sqlWinner);
        $stmtWinner->bind_param("i", $lotId);
        $stmtWinner->execute();
        $winnerResult = $stmtWinner->get_result();
        $winner = $winnerResult->fetch_assoc();
        $winnerUserId = $winner ? $winner['user_id'] : NULL;
        $stmtWinner->close();

        // Обновляем статус лота и устанавливаем победителя
        $sqlUpdate = "UPDATE lots SET is_active = ?, user_id = ? WHERE id = ?";
        $stmtUpdate = $conn->prepare($sqlUpdate);
        $stmtUpdate->bind_param("iii", $isActive, $winnerUserId, $lotId);
        $stmtUpdate->execute();
        $stmtUpdate->close();
    } else {
        // Если активируем лот, просто обновляем его статус
        $sql = "UPDATE lots SET is_active = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $isActive, $lotId);
        $stmt->execute();
        $stmt->close();
    }

    $conn->close();
}

function addLot($title, $description, $image, $startPrice, $endTime, $latitude, $longitude)
{
    $conn = connectDB();
    $sql = "INSERT INTO lots (title, description, image, start_price, end_time, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssdsdd", $title, $description, $image, $startPrice, $endTime, $latitude, $longitude);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}
