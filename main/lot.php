<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Детальный просмотр лота</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-3">
        <div id="lotInfo"></div>
        <div id="map" style="width: 100%; height: 400px;"></div>
        <form id="bidForm" class="mt-3">
            <div class="form-group">
                <input type="number" class="form-control" id="bidAmount" placeholder="Сумма ставки">
            </div>
            <button type="submit" class="btn btn-primary">Сделать ставку</button>
        </form>
    </div>

    <script src="https://api-maps.yandex.ru/2.1/?apikey=f0d0dd29-a9fa-4e6b-83c7-c27ef4f17eeb&lang=ru_RU" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            var lotId = new URLSearchParams(window.location.search).get('lot_id');
            var map;

            function initMap(latitude, longitude) {
                if (!map) {
                    map = new ymaps.Map('map', {
                        center: [latitude, longitude],
                        zoom: 14,
                    });
                    var placemark = new ymaps.Placemark([latitude, longitude]);
                    map.geoObjects.add(placemark);
                }
            }

            function loadLotInfo() {
                $.ajax({
                    url: 'lot_handler.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        lotId: lotId
                    },
                    success: function(response) {
                        if (response.success) {
                            var lot = response.data;
                            $('#lotInfo').html(
                                '<h3>' + lot.title + '</h3>' +
                                '<img src="' + lot.image + '" alt="' + lot.title + '" style="width:300px; height: auto;">' +
                                '<p>' + lot.description + '</p>' +
                                '<p>Текущая максимальная ставка: ' + (lot.currentBid === null ? lot.start_price : lot.currentBid) + '</p>' +
                                '<p>Ваша максимальная ставка: ' + (lot.userBid ? lot.userBid : 'Нет ставок') + '</p>'
                            );
                            initMap(lot.latitude, lot.longitude);
                        } else {
                            alert(response.error);
                        }
                    }
                });
            }

            $('#bidForm').on('submit', function(e) {
                e.preventDefault();
                var bidAmount = $('#bidAmount').val().trim();

                if (!bidAmount || isNaN(bidAmount)) {
                    alert('Введите корректную сумму ставки.');
                    return;
                }

                $.ajax({
                    url: 'lot_handler.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        lotId: lotId,
                        bidAmount: bidAmount
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Ставка успешно сделана.');
                            loadLotInfo();
                        } else {
                            alert(response.error);
                        }
                    }
                });
            });

            ymaps.ready(loadLotInfo);
            setInterval(loadLotInfo, 5000);
        });
    </script>

</body>

</html>
