
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?= csrf_meta() ?>

    <title>Комментарии | Fullstack Test</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f4f6fa;
            color: #1e293b;
            font-family: Arial, sans-serif;
        }

        .page-container {
            max-width: 850px;
            margin: 50px auto;
        }

        .main-card {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.05);
        }

        .comment-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            background: #fff;
        }

        .comment-card strong {
            overflow-wrap: anywhere;
        }

        .comment-date {
            color: #64748b;
            font-size: 14px;
        }

        .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
        }

        .section-title {
            font-weight: 700;
            margin-bottom: 22px;
        }

        textarea {
            resize: vertical;
        }

        /* Мобильная версия */
        @media (max-width: 576px) {

            .page-container {
                margin: 15px auto;
            }

            .page-container h1 {
                font-size: 2rem;
                line-height: 1.2;
            }

            .page-container > .mb-4 p {
                font-size: 0.95rem;
                line-height: 1.5;
            }

            .main-card {
                padding: 18px;
            }

            .section-title {
                font-size: 1.3rem;
            }

            .comment-card {
                padding: 15px;
            }

            .comment-card > .d-flex {
                flex-direction: column;
                align-items: stretch !important;
                gap: 8px;
            }

            .comment-card > .d-flex > div {
                width: 100%;
                margin-right: 0 !important;
            }

            .comment-card strong {
                overflow-wrap: anywhere;
                font-size: 1rem;
            }

            .comment-card .delete-comment {
                align-self: flex-end;
                margin: 8px 0 0;
            }

            .btn-primary {
                font-size: 1rem;
                white-space: normal;
            }
        }
    </style>
</head>

<body>

<div class="container page-container">

    <div class="mb-4">
        <h1 class="font-weight-bold">Комментарии</h1>

        <p class="text-muted">
            Оставляйте комментарии и просматривайте записи других пользователей.
        </p>
    </div>

    <main class="main-card">

        <h2 class="h4 section-title">
            Список комментариев
        </h2>

        <!-- Сортировка -->
        <div class="row mb-4">

            <div class="col-md-6 mb-3">
                <label for="sort">Сортировать по</label>

                <select id="sort" class="form-control">
                    <option value="date">Дате создания</option>
                    <option value="id">ID комментария</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="order">Порядок</label>

                <select id="order" class="form-control">
                    <option value="desc">По убыванию</option>
                    <option value="asc">По возрастанию</option>
                </select>
            </div>

        </div>

        <!-- Сообщения -->
        <div id="message"
             role="status"
             aria-live="polite">
        </div>

        <!-- Список комментариев -->
        <div id="commentsList" aria-live="polite">
            <p class="text-muted">
                Загрузка комментариев...
            </p>
        </div>

        <!-- Пагинация -->
        <div class="d-flex flex-wrap align-items-center
                    justify-content-center mb-4">

            <button id="prevPage"
                    type="button"
                    class="btn btn-outline-secondary btn-sm mr-2">
                Назад
            </button>

            <label for="pageSelect" class="sr-only">
                Номер страницы
            </label>

            <select id="pageSelect"
                    class="form-control form-control-sm"
                    style="width: 70px;">
                <option value="1">1</option>
            </select>

            <span id="pageInfo" class="mx-2">
                из 1
            </span>

            <button id="nextPage"
                    type="button"
                    class="btn btn-outline-secondary btn-sm">
                Далее
            </button>

        </div>

        <hr class="my-4">

        <!-- Форма комментария -->
        <h2 class="h4 section-title">
            Оставить комментарий
        </h2>

        <form id="commentForm" novalidate>

            <!-- Email -->
            <div class="form-group">
                <label for="name">Email</label>

                <input type="email"
                       class="form-control"
                       id="name"
                       name="name"
                       maxlength="254"
                       placeholder="example@mail.com"
                       required>

                <div class="invalid-feedback">
                    Введите корректный email.
                </div>
            </div>

            <!-- Дата -->
            <div class="form-group">
                <label for="date">Дата создания</label>

                <input type="date"
                       class="form-control"
                       id="date"
                       name="date"
                       required>

                <div class="invalid-feedback">
                    Выберите дату.
                </div>
            </div>

            <!-- Текст комментария -->
            <div class="form-group">
                <label for="text">Комментарий</label>

                <textarea class="form-control"
                          id="text"
                          name="text"
                          rows="4"
                          maxlength="5000"
                          placeholder="Напишите комментарий..."
                          required></textarea>

                <div class="invalid-feedback">
                    Введите текст комментария.
                </div>
            </div>

            <!-- Отправка -->
            <button type="submit"
                    id="submitComment"
                    class="btn btn-primary btn-block">
                Опубликовать комментарий
            </button>

        </form>

    </main>

</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Логика комментариев -->
<script src="/js/comments.js"></script>

</body>
</html>
