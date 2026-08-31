<?php

namespace App\Tests\Unit;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use App\Service\TodoService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilder;

class TodoServiceTest extends TestCase
{

    public function testGetTodo(): void
    { //Mock

      $todo = new Todo('je suis la ');
       $repository = $this->createMock(TodoRepository::class); // PHPUnit crée un faux TodoRepository.
        $repository
            ->expects($this->once()) // On s'attend à ce que la méthode find soit appelée une fois.
            ->method('find') // On spécifie la méthode que l'on veut simuler.
            ->with(2) // On s'attend à ce que la méthode find soit appelée avec l'argument 1.
            ->willReturn($todo); // On spécifie ce que la méthode find doit retourner.

            //Injecter le Mock dans le service
        $service = new TodoService($repository);

            $result = $service->getTodo(2);
            $this->assertSame($todo, $result);

            }


            public function testGetReturnsNullwhenTodoDoesNotExist() : void
            {
                $repository = $this->createMock(TodoRepository::class);
                $repository
                    ->expects($this->once())
                    ->method('find')
                    ->with(999)
                    ->willReturn(null);

                $service = new TodoService($repository);

                $result = $service->getTodo(999);

                $this->assertNull($result);
            }

            // management of the exception when the todo does not exist
            public function testGetTodoThrowsExceptionWhenTodoDoesNotExist(): void
{
    $repository = $this->createMock(TodoRepository::class);

    $repository
        ->expects($this->once())
        ->method('find')
        ->with(999)
        ->willReturn(null);

    $this->expectException(\RuntimeException::class);
    $this->expectExceptionMessage('Todo not found');

    $service = new TodoService($repository);

    $service->getTodo(999);
}


// Test for getting a todo with different IDs in a single test method using a data provider
#[DataProvider('todoIds')]
public function testGetTodoWithDifferentIds(int $id): void
{
    $todo = new Todo('Je suis une Todo');

    $repository = $this->createMock(TodoRepository::class);

    $repository
        ->expects($this->once())
        ->method('find')
        ->with($id)
        ->willReturn($todo);

    $service = new TodoService($repository);

    $result = $service->getTodo($id);

    $this->assertSame($todo, $result);
}

public static function todoIds(): array
{
    return [
        [1],
        [2],
        [10],
    ];
}
}
