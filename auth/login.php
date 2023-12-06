<?php
session_start();
if (isset($_SESSION["user_id"])) {
    header("Location: ../main/index.php");
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-md-offset-3">
                <h2 class="text-center">Вход в систему</h2>
                <form id="loginForm">
                    <div class="form-group">
                        <label for="login">Логин</label>
                        <input type="text" class="form-control" id="login" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" class="form-control" id="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Войти</button>
                    <a href="register.php">Регистрация</a>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery и Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                var login = $('#login').val().trim();
                var password = $('#password').val().trim();

                // Валидация полей
                if (login === "" || password === "" || !/^[a-zA-Z0-9]+$/.test(login)) {
                    alert('Поля должны быть заполнены. Разрешены только английские буквы и цифры.');
                    return;
                }

                $.ajax({
                    url: 'login_handler.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        login: login,
                        password: password
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = '../main/index.php';
                        } else {
                            alert(response.error);
                        }
                    }
                });
            });
        });
    </script>

</body>

</html>
