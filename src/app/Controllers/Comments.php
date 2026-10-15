<?php

namespace App\Controllers;

use App\Models\CommentModel;

class Comments extends BaseController
{
    // Получение списка комментариев
    public function index()
    {
        $model = new CommentModel();

        $sort = $this->request->getGet('sort');
        $order = strtolower((string) $this->request->getGet('order'));

        $sort = in_array($sort, ['id', 'date'], true)
            ? $sort : 'date';

        $order = $order === 'asc' ? 'ASC' : 'DESC';

        $page = filter_var(
            $this->request->getGet('page') ?? 1,
            FILTER_VALIDATE_INT
        );

        $page = $page !== false && $page > 0 ? $page : 1;

        $perPage = 3;
        $total = $model->countAllResults();
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);

        $query = $model->orderBy($sort, $order);

        if ($sort === 'date') {
            $query->orderBy('id', $order);
        }

        $comments = $query->findAll(
            $perPage,
            ($page - 1) * $perPage
        );

        return $this->response->setJSON([
            'comments' => $comments,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ]);
    }

    // Добавление комментария
    public function store()
    {
        $name = $this->request->getPost('name');
        $text = $this->request->getPost('text');
        $date = $this->request->getPost('date');

        $data = [
            'name' => is_string($name) ? trim($name) : '',
            'text' => is_string($text) ? trim($text) : '',
            'date' => is_string($date) ? trim($date) : ''
        ];

        $rules = [
            'name' => 'required|valid_email|max_length[254]',
            'text' => 'required|max_length[5000]',
            'date' => 'required|valid_date[Y-m-d]'
        ];

        if (!$this->validateData($data, $rules)) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'success' => false,
                    'errors' => $this->validator->getErrors()
                ]);
        }

        $model = new CommentModel();
        $id = $model->insert($data);

        if ($id === false) {
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'error' => 'Не удалось сохранить комментарий'
                ]);
        }

        return $this->response
            ->setStatusCode(201)
            ->setJSON([
                'success' => true,
                'id' => $id
            ]);
    }

    // Удаление комментария
    public function destroy($id)
    {
        $model = new CommentModel();

        if (!$model->find($id)) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'success' => false,
                    'error' => 'Комментарий не найден'
                ]);
        }

        $model->delete($id);

        return $this->response->setJSON([
            'success' => true
        ]);
    }
}
