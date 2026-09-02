<?php

namespace App\Service;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use Doctrine\ORM\EntityManagerInterface;

class TodoService
{

    public  function __construct(
        private TodoRepository $todoRepository,
        private EntityManagerInterface $entityManager )
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

     public function createTodo(string $title): Todo
     {

    //  if (trim($title) === '') {
    //    throw new \InvalidArgumentException('Todo title cannot be empty');
    //  }
        $todo = new Todo();
        $todo->setTitle($title);

        $this->entityManager->persist($todo);
        $this->entityManager->flush();

        return $todo;
     }
}


