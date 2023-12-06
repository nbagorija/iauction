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
    <title>Админ панель</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-3">
        <h2>Административная панель</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Удалить</th>
                    <th>Активность</th>
                    <th>Победитель ID</th>
                </tr>
            </thead>
            <tbody id="lotTable"></tbody>
        </table>
        <button id="addLot" class="btn btn-primary">Добавить новый лот</button>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            function loadLots() {
                $.ajax({
                    url: 'panel_handler.php',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var lots = response.data;
                            $('#lotTable').html('');
                            lots.forEach(function(lot) {
                                $('#lotTable').append(
                                    '<tr>' +
                                    '<td>' + lot.id + '</td>' +
                                    '<td>' + lot.title + '</td>' +
                                    '<td><button class="deleteLot btn btn-danger" data-id="' + lot.id + '">Удалить</button></td>' +
                                    '<td><input type="checkbox" class="toggleActive" data-id="' + lot.id + '" ' + (lot.is_active === "1" ? 'checked' : '') + '></td>' +
                                    '<td>' + lot.user_id + '</td>' +
                                    '</tr>'
                                );
                            });
                        } else {
                            alert(response.error);
                        }
                    }
                });
            }

            $(document).on('click', '.deleteLot', function() {
                var lotId = $(this).data('id');
                $.ajax({
                    url: 'panel_handler.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'delete',
                        lotId: lotId
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Лот удален');
                            loadLots();
                        } else {
                            alert(response.error);
                        }
                    }
                });
            });

            $(document).on('change', '.toggleActive', function() {
                var lotId = $(this).data('id');
                var isActive = $(this).is(':checked') ? 1 : 0;
                $.ajax({
                    url: 'panel_handler.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'toggleActive',
                        lotId: lotId,
                        isActive: isActive
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Статус лота изменен');
                        } else {
                            alert(response.error);
                        }
                    }
                });
            });

            $('#addLot').click(function() {
                window.location.href = 'add_lot.php';
            });

            loadLots();
            setInterval(loadLots, 5000);
        });
    </script>

</body>

</html>
