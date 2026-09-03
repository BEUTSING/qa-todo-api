<?php

namespace App\Controller;

use App\Service\TodoService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TodoController
{
    public function __construct(
        private TodoService $todoService
    ) {
    }

    // CREATE
    #[Route('/api/todos', name: 'api_todo_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $title = $data['title'] ?? '';

        try {
            $todo = $this->todoService->createTodo($title);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        }

        return new JsonResponse([
            'id' => $todo->getId(),
            'title' => $todo->getTitle(),
        ], Response::HTTP_CREATED);
    }

    // GET ALL
    #[Route('/api/todos', name: 'api_todo_list', methods: ['GET'])]
    public function getAll(): JsonResponse
    {
        $todos = $this->todoService->getAllTodos();

        $data = [];

        foreach ($todos as $todo) {
            $data[] = [
                'id' => $todo->getId(),
                'title' => $todo->getTitle(),
            ];
        }

        return new JsonResponse($data);
    }

    // GET BY ID
    #[Route('/api/todos/{id}', name: 'api_todo_show', methods: ['GET'])]
    public function getById(int $id): JsonResponse
    {
        try {
            $todo = $this->todoService->getTodo($id);
        } catch (\RuntimeException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse([
            'id' => $todo->getId(),
            'title' => $todo->getTitle(),
        ]);
    }

    // UPDATE
    #[Route('/api/todos/{id}', name: 'api_todo_update', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $title = $data['title'] ?? '';

        try {
            $todo = $this->todoService->updateTodo($id, $title);
        } catch (\RuntimeException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse([
            'id' => $todo->getId(),
            'title' => $todo->getTitle(),
        ]);
    }

    // DELETE
    #[Route('/api/todos/{id}', name: 'api_todo_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->todoService->deleteTodo($id);
        } catch (\RuntimeException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        }

        return new JsonResponse([
            'message' => 'Todo deleted successfully'
        ]);
    }
}
