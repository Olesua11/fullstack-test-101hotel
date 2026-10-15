$(function () {

    // CSRF защ AJAX запр
    $.ajaxSetup({
        beforeSend: function (xhr, settings) {
            if (!/^(GET|HEAD|OPTIONS)$/i.test(settings.type)) {
                const csrf = $('meta[name="X-CSRF-TOKEN"]');

                xhr.setRequestHeader(
                    csrf.attr('name'),
                    csrf.attr('content')
                );
            }
        }
    });

    const api = '/api/comments';
    let currentPage = 1;
    let totalPages = 1;

    function showMessage(text, type = 'danger') {
        const alert = $('<div>')
            .addClass('alert alert-' + type)
            .attr('role', 'alert')
            .text(text);

        $('#message').empty().append(alert);
    }

    function renderComments(data) {
        const list = $('#commentsList').empty();
        const comments = data.comments || [];

        if (comments.length === 0) {
            list.append(
                $('<p>')
                    .addClass('text-muted')
                    .text('Комментариев пока нет. Будьте первым!')
            );
        }

        comments.forEach(function (comment) {
            const date = String(comment.date || '').replace(
                /^(\d{4})-(\d{2})-(\d{2})$/,
                '$3.$2.$1'
            );

            const author = $('<strong>')
                .addClass('d-block text-break')
                .text(comment.name);

            const dateElement = $('<small>')
                .addClass('comment-date')
                .text(date);

            const deleteButton = $('<button>')
                .attr('type', 'button')
                .attr('data-id', comment.id)
                .addClass('btn btn-outline-danger btn-sm delete-comment')
                .text('Удалить');

            const header = $('<div>')
                .addClass('d-flex justify-content-between align-items-start mb-2')
                .append(
                    $('<div>').addClass('mr-3').append(author, dateElement),
                    deleteButton
                );

            const text = $('<p>')
                .addClass('mb-0 text-break')
                .css('white-space', 'pre-wrap')
                .text(comment.text);

            const card = $('<article>')
                .addClass('comment-card')
                .append(header, text);

            list.append(card);
        });

        currentPage = Number(data.page) || 1;
        totalPages = Math.max(1, Number(data.totalPages) || 1);

        const pageSelect = $('#pageSelect').empty();

        for (let i = 1; i <= totalPages; i++) {
            $('<option>').val(i).text(i).appendTo(pageSelect);
        }

        pageSelect.val(String(currentPage));

        $('#pageInfo').text('из ' + totalPages);
        $('#prevPage').prop('disabled', currentPage <= 1);
        $('#nextPage').prop('disabled', currentPage >= totalPages);
    }

    function loadComments() {
        $('#commentsList').text('Загрузка комментариев...');

        $.ajax({
            url: api,
            method: 'GET',
            dataType: 'json',
            data: {
                page: currentPage,
                sort: $('#sort').val(),
                order: $('#order').val()
            }
        })
        .done(renderComments)
        .fail(function (xhr) {
            $('#commentsList').empty();
            showMessage('Не удалось загрузить комментарии. HTTP ' + xhr.status);
        });
    }

    // Сортировка

$('#sort, #order').on('change', function () {
    $('#message').empty();
    currentPage = 1;
    loadComments();
});


    // Пагинация
    $('#pageSelect').on('change', function () {
        currentPage = Number($(this).val()) || 1;
        loadComments();
    });

    $('#prevPage').on('click', function () {
        if (currentPage > 1) {
            currentPage--;
            loadComments();
        }
    });

    $('#nextPage').on('click', function () {
        if (currentPage < totalPages) {
            currentPage++;
            loadComments();
        }
    });

    // Добавление комментария
    $('#commentForm').on('submit', function (event) {
        event.preventDefault();
        $('#message').empty();

        $(this).addClass('was-validated');

        if (!this.checkValidity()) {
            return;
        }

        const form = $(this);
        const button = $('#submitComment');

        button.prop('disabled', true).text('Публикация...');

        $.ajax({
            url: api,
            method: 'POST',
            data: form.serialize(),
            dataType: 'json'
        })
        .done(function () {
            form[0].reset();
            form.removeClass('was-validated');

            currentPage = 1;
            showMessage('Комментарий успешно добавлен!', 'success');
            loadComments();
        })
        .fail(function (xhr) {
            const errors = xhr.responseJSON?.errors;

            const message = errors
                ? Object.values(errors).join(' ')
                : 'Не удалось добавить комментарий. HTTP ' + xhr.status;

            showMessage(message);
        })
        .always(function () {
            button.prop('disabled', false)
                  .text('Опубликовать комментарий');
        });
    });

    // Удаление комментария
    $('#commentsList').on('click', '.delete-comment', function () {
        const id = $(this).attr('data-id');

        if (!confirm('Удалить этот комментарий?')) {
            return;
        }

        $.ajax({
            url: api + '/' + encodeURIComponent(id),
            method: 'DELETE',
            dataType: 'json'
        })
        .done(function () {
            showMessage('Комментарий удалён.', 'success');
            loadComments();
        })
        .fail(function (xhr) {
            showMessage('Ошибка удаления. HTTP ' + xhr.status);
        });
    });


    // Убираем устаревшие сообщения при навигации
    $('#sort, #order, #pageSelect').on('change', function () {
        $('#message').empty();
    });

    $('#prevPage, #nextPage').on('click', function () {
        $('#message').empty();
    });

    // Загрузка при открытии страницы
    loadComments();
});
