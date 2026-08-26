<?php

namespace App\Tests\Unit;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use App\Service\TodoService;
use PHPUnit\Framework\TestCase;

class TodoServiceTest extends TestCase
{

    public function testGetTodo(): void
    { //Mock

      $todo = new Todo('je suis la ');
       $repository = $this->createMock(TodoRepository::class); // PHPUnit crée un faux TodoRepository.
        $repository
           // ->expects($this->once()) // On s'attend à ce que la méthode find soit appelée une fois.
            ->method('find') // On spécifie la méthode que l'on veut simuler.
           // ->with(1) // On s'attend à ce que la méthode find soit appelée avec l'argument 1.
            ->willReturn($todo); // On spécifie ce que la méthode find doit retourner.

            //Injecter le Mock dans le service
        $service = new TodoService($repository);

            $result = $service->getTodo(1);
            $this->assertSame($todo, $result);

            }
}
