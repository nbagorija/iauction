<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
}
include_once '../db.php';
updateLotStatus();
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-3">
        <h2>Личный кабинет</h2>
        <div id="userInfo"></div>
        <h3>Выигранные лоты</h3>
        <div id="wonLots"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=f0d0dd29-a9fa-4e6b-83c7-c27ef4f17eeb&lang=ru_RU" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            function initMap(mapId, latitude, longitude) {
                var map = new ymaps.Map(mapId, {
                    center: [latitude, longitude],
                    zoom: 14
                });
                var placemark = new ymaps.Placemark([latitude, longitude]);
                map.geoObjects.add(placemark);

                window[mapId] = map;
            }

            function loadProfileInfo() {
                $.ajax({
                    url: 'profile_handler.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var user = response.data.user;
                            var wonLots = response.data.wonLots;
                            $('#userInfo').html(
                                '<p>Логин: ' + user.login + '</p>' +
                                '<p>Имя: ' + user.username + '</p>' +
                                '<p>Email: ' + user.email + '</p>'
                            );
                            $('#wonLots').html('');
                            wonLots.forEach(function(lot, index) {
                                var mapId = 'map' + index;
                                $('#wonLots').append(
                                    '<div class="card mb-3">' +
                                    '<div class="card-body">' +
                                    '<h5 class="card-title">' + lot.title + '</h5>' +
                                    '<p class="card-text">Ставка: ' + lot.bid + '</p>' +
                                    '<div id="' + mapId + '" style="width: 100%; height: 200px;"></div>' +
                                    '</div>' +
                                    '</div>'
                                );
                                ymaps.ready(function() {
                                    initMap(mapId, lot.latitude, lot.longitude);
                                });
                            });
                        } else {
                            alert(response.error);
                        }
                    }
                });
            }

            loadProfileInfo();
            setInterval(loadProfileInfo, 10000);
        });
    </script>

</body>

</html>
