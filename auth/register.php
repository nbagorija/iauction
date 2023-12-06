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
    <title>Регистрация</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-md-offset-3">
                <h2 class="text-center">Регистрация</h2>
                <form id="registerForm">
                    <div class="form-group">
                        <label for="login">Логин</label>
                        <input type="text" class="form-control" id="login" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Электронная почта</label>
                        <input type="email" class="form-control" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Имя пользователя</label>
                        <input type="text" class="form-control" id="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Пароль</label>
                        <input type="password" class="form-control" id="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Подтверждение пароля</label>
                        <input type="password" class="form-control" id="confirmPassword" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
                    <a href="login.php">Авторизация</a>
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
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                var login = $('#login').val().trim();
                var email = $('#email').val().trim();
                var username = $('#username').val().trim();
                var password = $('#password').val().trim();
                var confirmPassword = $('#confirmPassword').val().trim();

                // Валидация полей
                if (!login || !email || !username || !password || password !== confirmPassword || !/^[a-zA-Z0-9]+$/.test(login)) {
                    alert('Проверьте введенные данные.');
                    return;
                }

                var emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
                if (!emailRegex.test(email)) {
                    alert('Некорректный формат электронной почты.');
                    return;
                }

                $.ajax({
                    url: 'register_handler.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        login: login,
                        email: email,
                        username: username,
                        password: password
                    },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'login.php';
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
