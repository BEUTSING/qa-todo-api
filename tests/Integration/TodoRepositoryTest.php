<?php

namespace App\Tests\Integration;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TodoRepositoryTest extends KernelTestCase
{
//
    private $entityManager;
private $repository;
// le setUp() est exécuté avant chaque test pour initialiser l'environnement de test. Il démarre le noyau de l'application Symfony, récupère le conteneur de services et initialise l'EntityManager et le repository pour les tests d'intégration.
protected function setUp(): void
{
    parent::setUp();

    self::bootKernel();

    $container = static::getContainer();

    $this->entityManager = $container->get('doctrine')->getManager();
    $this->repository = $container->get(TodoRepository::class);
}

//
    public function testFindTodo(): void
    {
        self::bootKernel(); // Démarre le noyau de l'application Symfony pour les tests d'intégration.

        $container = static::getContainer(); // Récupère le conteneur de services de Symfony pour accéder aux services et aux dépendances.


        $repository = $container->get(TodoRepository::class);

        $todo = new Todo();
        $todo->setTitle('Apprendre les tests d’intégration');

        $entityManager = $container->get('doctrine')->getManager();

        $entityManager->persist($todo);
        $entityManager->flush();

        $id = $todo->getId();

        $result = $repository->find($id);

        $this->assertNotNull($result);
        $this->assertSame($id, $result->getId());
        $this->assertSame(
            'Apprendre les tests d’intégration',
            $result->getTitle()
        );
    }

//     public function testFindTodoReturnsNullWhenTodoDoesNotExist(): void
// {
//         self::bootKernel();

//         $container = static::getContainer();

//         $repository = $container->get(TodoRepository::class);

//         $result = $repository->find(999);

//         $this->assertNull($result);

// }

public function testFindTodoReturnsNullWhenTodoDoesNotExist(): void
{
    $result = $this->repository->find(999999);

    $this->assertNull($result);
}

// user the setUp() method to initialize the entity manager and repository for integration tests. This avoids repeating the same code in each test method.
public function testCreateTodoj(): void
{
    $todo = new Todo();
    $todo->setTitle('Apprendre PHPUnit');

    $this->entityManager->persist($todo);
    $this->entityManager->flush();

    $this->assertNotNull($todo->getId());

    $result = $this->repository->find($todo->getId());

    $this->assertNotNull($result);
    $this->assertSame($todo->getId(), $result->getId());
    $this->assertSame('Apprendre PHPUnit', $result->getTitle());
}
// public function testCreateTodo(): void
// {
//     self::bootKernel();

//     $container = static::getContainer();
//     $repository = $container->get(TodoRepository::class);

//     $entityManager = $container->get('doctrine')->getManager();

//     $todo = new Todo();
//     $todo->setTitle('Apprendre PHPUnit');

//     $entityManager->persist($todo);
//     $entityManager->flush();

//     $this->assertNotNull($todo->getId());

//     $result = $repository->find($todo->getId());

//     $this->assertNotNull($result);
//     $this->assertSame($todo->getId(), $result->getId());
//     $this->assertSame('Apprendre PHPUnit', $todo->getTitle());
// }

   public function testUpdateTodo(): void
{
    self::bootKernel();

    $container = static::getContainer();

    $entityManager = $container->get('doctrine')->getManager();
    $repository = $container->get(TodoRepository::class);

    // Création
    $todo = new Todo();
    $todo->setTitle('Apprendre PHPUnit');

    $entityManager->persist($todo);
    $entityManager->flush();

    $id = $todo->getId();

    // Modification
    $todo->setTitle('Apprendre les tests QA');

    $entityManager->flush();

    // Lecture depuis la base
    $entityManager->clear();// Vide le gestionnaire d'entités pour forcer la récupération depuis la base de données.

    $result = $repository->find($id);
    $result2 = $repository->findAll();

    $this->assertCount(6, $result2);
    // Vérifications
    $this->assertNotNull($result);
    $this->assertSame('Apprendre les tests QA', $result->getTitle());
}

public function testDeleteTodo(): void
{
    self::bootKernel();

    $container = static::getContainer();

    $entityManager = $container->get('doctrine')->getManager();
    $repository = $container->get(TodoRepository::class);

    // Création
    $todo = new Todo();
    $todo->setTitle('Todo à supprimer');

    $entityManager->persist($todo);
    $entityManager->flush();

    $id = $todo->getId();

    // Suppression
    $entityManager->remove($todo);
    $entityManager->flush();

    // On vide le contexte Doctrine
    $entityManager->clear();

    // Recherche
    $result = $repository->find($id);

    // Vérification
    $this->assertNull($result);
}
}
