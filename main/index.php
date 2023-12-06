<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
}
include_once '../db.php';

$isAdmin = isAdmin($_SESSION["user_id"]);
?>

<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Главная страница</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <header class="bg-primary text-white p-3 mb-3 rounded">
            <nav class="nav nav-pills">
                <a class="nav-link text-white" href="index.php">Главная</a>
                <a class="nav-link text-white" href="../user/profile.php">Личный кабинет</a>
                <?php if ($isAdmin) : ?>
                    <a class="nav-link text-white" href="../admin/panel.php">Админ панель</a>
                <?php endif; ?>
                <a class="nav-link text-white" href="../auth/logout.php">Выйти</a>
            </nav>
        </header>

        <div class="row" id="lotsContainer"></div>
    </div>

    <!-- jQuery, Popper.js, и Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            function calculateRemainingTime(endTime) {
                var end = new Date(endTime);
                var now = new Date();
                var diff = end - now;
                var days = Math.floor(diff / (1000 * 60 * 60 * 24));
                var hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((diff % (1000 * 60)) / 1000);
                return days + " дней " + hours + " часов " + minutes + " минут " + seconds + " секунд";
            }

            function loadLots() {
                $.ajax({
                    url: 'index_handler.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#lotsContainer').html('');
                            response.data.forEach(function(lot) {
                                var remainingTime = calculateRemainingTime(lot.end_time);
                                $('#lotsContainer').append(
                                    '<div class="col-md-6">' +
                                    '<div class="card mb-4">' +
                                    '<img src="' + lot.image + '" class="card-img-top" alt="' + lot.title + '" style="height: 100px; object-fit: cover;">' +
                                    '<div class="card-body">' +
                                    '<h5 class="card-title"><a href="lot.php?lot_id=' + lot.id + '">' + lot.title + '</a></h5>' +
                                    '<p class="card-text">Текущая ставка: ' + (lot.currentBid === null ? lot.start_price : lot.currentBid) + '</p>' +
                                    '<p class="card-text">Окончание аукциона: ' + remainingTime + '</p>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>'
                                );
                            });
                        } else {
                            alert(response.error);
                        }
                    }
                });
            }

            loadLots();
            setInterval(loadLots, 1000);
        });
    </script>

</body>

</html>
