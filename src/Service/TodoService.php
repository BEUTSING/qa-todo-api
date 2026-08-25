<?php

namespace App\Service;

use App\Repository\TodoRepository;

class Todoservice
{
    public  function __construct(
        private  TodoRepository $todoRepository
    )
    {
    }

     public function getTodoTitle(int $id): ?string
    {
        $todo = $this->todoRepository->find($id);

        return $todo?->getTitle();
    }
}

