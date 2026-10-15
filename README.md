# Решение тестового задания — Fullstack Developer

Веб-приложение для управления комментариями, разработанное на PHP, CodeIgniter 4, MySQL, jQuery и Bootstrap 4.

## Реализованный функционал

- Просмотр списка комментариев.
- Добавление комментариев без перезагрузки страницы.
- Удаление комментариев через AJAX.
- Пагинация по 3 комментария на страницу.
- Возможность перехода на конкретную страницу.
- Сортировка по ID и дате.
- Сортировка по возрастанию и убыванию.
- Проверка корректности email на клиенте и сервере.
- Адаптивный интерфейс для мобильных устройств.
- Отображение ошибок и уведомлений.
- CSRF-защита запросов.
- Безопасный вывод пользовательского текста.

## Технологии

- PHP 8.2
- CodeIgniter 4
- MySQL 8
- JavaScript / jQuery 3
- Bootstrap 4
- Docker / Docker Compose

## Запуск проекта

### 1. Подготовка окружения

Для запуска потребуются Docker и Docker Compose.

Скопируйте файл `.env.example` в `.env`.

Укажите в `.env` локальные настройки базы данных и пароль MySQL.

### 2. Запуск контейнеров

```bash
docker compose up -d --build
```

### 3. Установка PHP-зависимостей

```bash
docker compose exec -w /var/www/html web composer install
```

### 4. Создание таблиц базы данных

```bash
docker compose exec -w /var/www/html web php spark migrate
```

### 5. Открытие приложения

После запуска приложение доступно по адресу:

http://localhost/

Интерфейс управления базой данных Adminer:

http://localhost:8080/

## API

GET /api/comments — получение комментариев с пагинацией и сортировкой.

POST /api/comments — добавление комментария.

DELETE /api/comments/{id} — удаление комментария.

Для изменения данных используется CSRF-защита.

## Структура реализации

- `src/app/Controllers/Comments.php` — API комментариев.
- `src/app/Models/CommentModel.php` — модель комментариев.
- `src/app/Database/Migrations/` — миграции базы данных.
- `src/app/Views/comments.php` — пользовательский интерфейс.
- `src/public/js/comments.js` — AJAX-запросы и работа интерфейса.

---

## Исходное техническое задание


# Тестовое задание на основе Docker и CodeIgniter для Fullstack разработчика

## Первоначальная настройка

-   Устанавливаем Docker c [официального сайта](https://www.docker.com/products/docker-desktop) и [Docker Compose](https://docs.docker.com/compose/install/);
-   Для пользователей Windows дополнительно необходимо установить виртуальное ядро Linux, следуя данной [инструкции](https://docs.docker.com/desktop/install/windows-install/);
-   Собираем контейнер командой в папке проекта `docker-compose up -d`;
-   Инициализируем сервер:
    -   при запущенном контейнере в папке проекта запускаем команду `docker-compose exec web bash`;
    -   запускаем сборку `composer install`.

## Ошибка докера

В случае ошибки такого вида:

    Error response from daemon: pull access denied for nginx, repository does not exist or may require 'docker login': denied:
    <html>
    <body>
    <h1>403 Forbidden</h1>
    Since Docker is a US company, we must comply with US export control regulations.
    In an effort to comply with these, we now block all IP addresses that are located in Cuba, Iran, North Korea, Republic of Crimea, Sudan, and Syria.
    If you are not in one of these cities, countries, or regions and are blocked, please reach out to https://hub.docker.com/support/contact/
    </body>
    </html>

Нужно использовать прокси сервер

### Как его подключить

1. через конфиг докера (как зеркало docker.io)

    конфиг расположен в

    | операционная система     |          путь к файлу конфигурации          |
    | ------------------------ | :-----------------------------------------: |
    | Linux, обычная установка |           /etc/docker/daemon.json           |
    | Linux, режим rootless    |        ~/.config/docker/daemon.json         |
    | Windows                  |  C:\ProgramData\docker\config\daemon.json   |
    | Windows с Docker Desktop | C:\Users\<Пользователь>\.docker\daemon.json |

    конфиг:
    { "registry-mirrors" : [ "https://dockerhub.timeweb.cloud" ] }

    чтобы конфиг применился потребуется перезапустить конфигурацию:
    systemctl reload docker


    теперь при попытке загрузки образа, Docker будет сначала пытаться использовать прокси.

1. явное указание адреса

    ```
    docker pull dockerhub.timeweb.cloud/library/alpine:latest

    docker pull dockerhub.timeweb.cloud/openresty/openresty:latest
    ```

## Описание записи

-   name -  почта создателя;
-   text - Текст комментария;
-   date - Дата создания комментария в строковом формате(выбирается создателем).

## Стек

- PHP 8.2;
- MYSQL 8;
- CodeIgniter 4;
- jQuery 3;
- Bootstrap 4.

## Задание

Создать сайт со списком комментариев.
Форма с добавлением комментариевдолжна располагаться под уже добавленными комментариями.

Требования к разработке:

-   добавление и удаление комментариев (желательно, без перезагрузки страницы);
-   постраничный просмотр комментариев (3 комментария на страницу c возможностью выбора конкретной);
-   сортировка по:
    -   id;
    -   дате добавления;
-   направления сортировки:
    -   по возрастанию;
    -   по убыванию;
-   использование валидации почты при вводе для пользователя (с отображением ошибки), а также на сервере;
-   использование адаптивной верстки;
-   использование jQuery.