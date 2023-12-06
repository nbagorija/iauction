<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
}
include_once '../db.php';

$isAdmin = isAdmin($_SESSION["user_id"]);
if (!$isAdmin) {
    header("Location: ../main/index.php");
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Добавление нового лота</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-3">
        <h2>Добавление нового лота</h2>
        <form id="addLotForm">
            <div class="form-group">
                <label for="title">Название:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Описание:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Изображение:</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <div class="form-group">
                <label for="startPrice">Начальная цена:</label>
                <input type="number" class="form-control" id="startPrice" name="start_price" required>
            </div>
            <div class="form-group">
                <label for="endTime">Время окончания:</label>
                <input type="datetime-local" class="form-control" id="endTime" name="end_time" required>
            </div>
            <div class="form-group">
                <label>Местоположение:</label>
                <div id="map" style="width: 100%; height: 400px;"></div>
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
            </div>
            <button type="submit" class="btn btn-primary">Добавить лот</button>
        </form>
    </div>

    <script src="https://api-maps.yandex.ru/2.1/?apikey=f0d0dd29-a9fa-4e6b-83c7-c27ef4f17eeb&lang=ru_RU" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            var map, placemark;

            function initMap() {
                map = new ymaps.Map('map', {
                    center: [59.85777516813396, 30.326726228493555],
                    zoom: 14
                });

                placemark = new ymaps.Placemark(map.getCenter(), {}, {
                    draggable: true
                });

                map.geoObjects.add(placemark);

                placemark.events.add('dragend', function() {
                    var coords = placemark.geometry.getCoordinates();
                    $('#latitude').val(coords[0]);
                    $('#longitude').val(coords[1]);
                });
            }

            $('#addLotForm').submit(function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: 'add_lot_handler.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Лот успешно добавлен');
                            window.location.href = "panel.php";
                        } else {
                            alert(response.error);
                        }
                    }
                });
            });

            ymaps.ready(initMap);
        });
    </script>

</body>

</html>
