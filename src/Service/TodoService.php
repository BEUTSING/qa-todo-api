<?php

namespace App\Service;

use App\Entity\Todo;
use App\Repository\TodoRepository;

class TodoService
{
    public  function __construct(
        private  TodoRepository $todoRepository
    )
    {
    }

    //  public function getTodo(int $id)
    // {
    //      $todo = $this->todoRepository->find($id);

    //     return $todo;
    // }

    public function getTodo(int $id): Todo
{
    $todo = $this->todoRepository->find($id);

    if ($todo === null) {
        throw new \RuntimeException('Todo not found');
    }

    return $todo;
}
}


