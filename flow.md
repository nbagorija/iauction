## Логин
**Путь к файлу** - `auth/login.php`

**Путь к файлу для обработки AJAX и HTTP запросов (handler)** - `auth/login_handler.php`

#### Front:
1. Весь контент обернуть в контейнер и контейнер оцентрован
2. Внутри контейнера заголовок "Вход в систему"
3. Поле для заполнения "Логина"
4. Поле для заполнения "Пароля"
5. Синяя кнопка "Войти"
6. Текст "Регистрация" - гипперсылка для перевода на страницу регистрации

#### Backend:
1. Все формы обязательны для ввода и допустимы только английские буквы с цифрами. Все поля не пустые (после trim)
3. При нажатии кнопки "Войти" посылается запрос POST в handler

---

## Регистрация
**Путь к файлу**: `auth/register.php`

**Путь к файлу для обработки AJAX и HTTP запросов**: `auth/register_handler.php`

#### Front:
1. Весь контент размещен в центрально выровненном контейнере.
2. Заголовок "Регистрация" в верхней части контейнера.
3. Поля для заполнения: "Логин", "Электронная почта", "Имя пользователя", "Пароль", "Подтверждение пароля".
4. Синяя кнопка "Зарегистрироваться".
5. Текст "Авторизация", - гиперссылка на страницу авторизации (логина).

#### Backend:
1. Все поля обязательны для заполнения. Только английские буквы и цифры. Имя можно и на русском. Все поля не пустые (после trim). Логин должен быть уникальным.
2. Проверка соответствия пароля и его подтверждения.
3. При ошибке ввода отображать сообщение об ошибке под заголовком в красном контейнере с использованием AJAX.
4. При нажатии на "Зарегистрироваться" отправляется POST-запрос в handler.

---

## Главная страница
**Путь к файлу**: `main/index.php`

**Путь к файлу для обработки AJAX и HTTP запросов**: `main/index_handler.php`

Только для авторизированных

#### Front
1. Header (Главная, Личный кабинет, Админ панель (если админ), выйти)
2. Карточки с лотами (маленькое изображение сверху, информация снизу: название лота, текущая ставка на нем (если текущей ставки нету, то показывать минимальную необходимую ставку), бегущее время (дни, часы, минуты, секунды))

#### Backend
1. При нажатии на название лота переводить на страницу детального просмотра лота
2. Интервальное обновление данных (ставок) каждые 5 секунд
3. Таймер для обновления времени на каждом лоте

---

## Детальный просмотр лотов
**Путь к файлу**: `main/lot.php`

**Путь к файлу для обработки AJAX и HTTP запросов**: `main/lot_handler.php`

Только для авторизированных

#### Front
1. Название лота
2. Средняя картинка лота
3. Описание лота
3. Текущая максимальная ставка среди всех пользователей
4. Текущая максимальная ставка текущего пользовател
5. Местоположение лота на карте yandex
5. Форма для отправки ставки
6. Кнопка для отправки ставки

#### Backend
1. При нажатии на кнопку отправки ставки, делать новую ставку, если она валидна (лот активен и ставка проходит валидацию).
2. Интервальное обновление данных (лота) каждые 5 секунд
3. Если лот больше не активен то вывести alert и перевести на главную страницу

---

## Личный кабинет

**Путь к файлу**: `user/profile.php`

**Путь к файлу для обработки AJAX и HTTP запросов**: `user/profile_handler.php`

Только для авторизированных

#### Front
1. Информация о пользователе
2. Карточки выигранных лотов (как на главной странице, только без бегущего времени, только ставка за которое выигран лот)

#### Backend
1. Интервальная подгрузка выигранных лотов каждые 5 секунд


---

## Админ панель

**Путь к файлу**: `admin/panel.php`

**Путь к файлу для обработки AJAX и HTTP запросов**: `admin/panel_handler.php`

Только для авторизированных и админов

#### Front
1. Таблица всех лотов
    - Заголовки (ID, Название, Удалить, Активность)
    - В строке удалить кнопка удалить
    - Встроке активность чекбокс
2. Кнопка добавить новый лот

#### Backend
1. Загрузка всех лотов
2. Возможность удаления лота без подтвержденя (при успехе отобразить alert)
3. Возможность менять активность лота без подтвержденя (при успехе отобразить alert), если текущее время сервера больше чем время окончания лота то вывести alert "Невозможно логически сделать лот активным"
4. Интервальное обновление каждые 5 секунд информации о лотах
5. Кнопка добавить новый лот ведет на страницу admin/add_lot.php


---

## Добавление новых лотов

**Путь к файлу**: `admin/add_lot.php`

**Путь к файлу для обработки AJAX и HTTP запросов**: `admin/add_lot_handler.php`

Только для авторизированных и админов

#### Front


#### Backend