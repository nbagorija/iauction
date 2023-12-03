## Техническое Задание для Интернет-Аукциона "IAuction"

### 1. Общие Сведения
**Название проекта**: "IAuction" - интернет-аукцион для онлайн-торгов.

**Цель проекта**: Создание платформы для организации и проведения онлайн-аукционов, предоставляющей возможность пользователям участвовать в торгах и делать ставки на различные лоты.

### 2. Функциональные Требования
- **Аутентификация и Регистрация**: Возможность регистрации и входа в систему для пользователей.
- **Роли и Права Пользователей**: Две основные роли – Администратор (управление аукционными лотами) и Посетитель (участие в аукционах, внесение ставок).
- **Интерактивность Интерфейса**: Применение AJAX для динамического обновления данных о лотах и ставках в реальном времени.
- **Интеграция с Внешними Сервисами**: Взаимодействие с API Яндекс Карт для отображения местоположения лотов.

### 3. Технические Требования и Стэк
- **Backend**: PHP как основной язык программирования.
- **Frontend**: jQuery для обработки AJAX-запросов и динамического обновления контента, Bootstrap для разработки пользовательского интерфейса.
- **База данных**: MySQL для хранения данных о пользователях, лотах и ставках.
- **Внешние сервисы**: Интеграция с API Яндекс Карт для отображения географического положения лотов.

### 4. Этапы Разработки
1. **Планирование**: Определение функций и структуры проекта, дизайн интерфейса.
2. **Настройка Среды Разработки**: Конфигурация сервера, установка PHP, настройка MySQL.
3. **Разработка Backend**: Реализация функционала управления пользователями, лотами и ставками.
4. **Разработка Frontend**: Создание интерфейса с применением Bootstrap, реализация клиентской логики с использованием jQuery.
5. **Интеграция с Yandex Maps**: Разработка функционала для отображения местоположения лотов на карте.
