<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoControllerTest extends WebTestCase
{
    public function testCreateTodoCon(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/todos',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'title' => 'Apprendre les tests fonctionnels',
            ])
        );

        // Vérifier le code HTTP
        $this->assertResponseStatusCodeSame(201);
        // Créer le deuxième Todo
    $client->request(
        'POST',
        '/api/todos',
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
        ],
        json_encode([
            'title' => 'Deuxième Todo',
        ])
    );

    $this->assertResponseStatusCodeSame(201);

        // Récupérer la réponse JSON
        $data = json_decode(
            $client->getResponse()->getContent(),
            true
        );

        // Vérifier que l'ID a été créé
        $this->assertNotNull($data['id']);

        // Vérifier le titre
        $this->assertSame(
            'Apprendre les tests fonctionnels',
            $data['title']
        );
    }
    // get all todos
    public function testGetAllTodos(): void
    {
        $client= static::createClient();

        $client->request(
            'GET',
            'api/todos',

        );
         $this->assertResponseStatusCodeSame(200);

    $data = json_decode(
        $client->getResponse()->getContent(),
        true
    );
         $this->assertIsArray($data); // Vérifier que la réponse est un tableau
    $this->assertGreaterThanOrEqual(2, count($data)); // Vérifier qu'il y a au moins 2 todos dans la réponse


    }

    // Test de mise à jour d'un Todo
    public function testUpdateTodo(): void
{
    $client = static::createClient();

    // Créer le Todo
    $client->request(
        'POST',
        '/api/todos',
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
        ],
        json_encode([
            'title' => 'Ancien titre',
        ])
    );

    $this->assertResponseStatusCodeSame(201);

    $data = json_decode(
        $client->getResponse()->getContent(),
        true
    );

    $id = $data['id'];

    // Modifier le Todo
    $client->request(
        'PUT',
        '/api/todos/' . $id,
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
        ],
        json_encode([
            'title' => 'Nouveau titre',
        ])
    );

    $this->assertResponseStatusCodeSame(200);

    $result = json_decode(
        $client->getResponse()->getContent(),
        true
    );

    $this->assertSame($id, $result['id']);

    $this->assertSame(
        'Nouveau titre',
        $result['title']
    );
}

public function testGetTodoById(): void
{
    $client = static::createClient();

    // Créer un Todo
    $client->request(
        'POST',
        '/api/todos',
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
        ],
        json_encode([
            'title' => 'Todo à récupérer',
        ])
    );

    $this->assertResponseStatusCodeSame(201);

    $data = json_decode(
        $client->getResponse()->getContent(),
        true
    );

    $id = $data['id'];

    // Récupérer le Todo
    $client->request(
        'GET',
        '/api/todos/' . $id
    );

    $this->assertResponseStatusCodeSame(200);

    $result = json_decode(
        $client->getResponse()->getContent(),
        true
    );

    $this->assertSame($id, $result['id']);
    $this->assertSame(
        'Todo à récupérer',
        $result['title']
    );
}
    // Test de mise à jour d'un Todo Todo inexistant
    public function testUpdateTodoNotFound(): void
{
    $client = static::createClient();

    $client->request(
        'PUT',
        '/api/todos/999999',
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
        ],
        json_encode([
            'title' => 'Nouveau titre',
        ])
    );

    $this->assertResponseStatusCodeSame(404);

    $data = json_decode(
        $client->getResponse()->getContent(),
        true
    );

    $this->assertSame(
        'Todo not found',
        $data['error']
    );
}
// Test de suppression d'un Todo
   public function testDeleteTodo(): void
{
    $client = static::createClient();

    // Créer le Todo à supprimer
    $client->request(
        'POST',
        '/api/todos',
        [],
        [],
        [
            'CONTENT_TYPE' => 'application/json',
        ],
        json_encode([
            'title' => 'Todo à supprimer',
        ])
    );

    // Vérifier que le Todo a bien été créé
    $this->assertResponseStatusCodeSame(201);

    // Récupérer les données du Todo créé
    $data = json_decode(
        $client->getResponse()->getContent(),
        true
    );

    // Récupérer l'ID généré par la base de données
    $id = $data['id'];

    // Supprimer le Todo que nous venons de créer
    $client->request(
        'DELETE',
        '/api/todos/' . $id
    );

    // Vérifier que la suppression a réussi
    $this->assertResponseStatusCodeSame(200);

    // Récupérer la réponse JSON
    $result = json_decode(
        $client->getResponse()->getContent(),
        true
    );

    // Vérifier le message de succès
    $this->assertSame(
        'Todo deleted successfully',
        $result['message']
    );
}
    }
