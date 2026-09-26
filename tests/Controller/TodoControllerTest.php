<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TodoControllerTest extends WebTestCase
{
    // Test de création d'un Todo
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

        // Vérifier que la création retourne le code HTTP 201
        $this->assertResponseStatusCodeSame(201);

        // Récupérer la réponse JSON
        $data = json_decode(
            $client->getResponse()->getContent(),
            true
        );

        // Vérifier que l'ID a bien été créé
        $this->assertNotNull($data['id']);

        // Vérifier que le titre correspond à celui envoyé
        $this->assertSame(
            'Apprendre les tests fonctionnels',
            $data['title']
        );
    }

    // Test de récupération de tous les Todo
    public function testGetAllTodos(): void
    {
        $client = static::createClient();

        // Créer le premier Todo
        $client->request(
            'POST',
            '/api/todos',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
            ],
            json_encode([
                'title' => 'Premier Todo',
            ])
        );

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

        // Récupérer tous les Todo
        $client->request(
            'GET',
            '/api/todos'
        );

        $this->assertResponseStatusCodeSame(200);

        // Récupérer la réponse JSON
        $data = json_decode(
            $client->getResponse()->getContent(),
            true
        );

        // Vérifier que la réponse est bien un tableau
        $this->assertIsArray($data);

        // Vérifier qu'il y a au moins 2 Todo
        $this->assertGreaterThanOrEqual(2, count($data));
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

        // Récupérer les données du Todo créé
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

        // Récupérer la réponse
        $result = json_decode(
            $client->getResponse()->getContent(),
            true
        );

        // Vérifier que l'ID est toujours le même
        $this->assertSame($id, $result['id']);

        // Vérifier que le titre a été modifié
        $this->assertSame(
            'Nouveau titre',
            $result['title']
        );
    }

    // Test de récupération d'un Todo par son ID
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

        // Récupérer les données du Todo créé
        $data = json_decode(
            $client->getResponse()->getContent(),
            true
        );

        $id = $data['id'];

        // Récupérer le Todo par son ID
        $client->request(
            'GET',
            '/api/todos/' . $id
        );

        $this->assertResponseStatusCodeSame(200);

        // Récupérer la réponse
        $result = json_decode(
            $client->getResponse()->getContent(),
            true
        );

        // Vérifier l'ID
        $this->assertSame($id, $result['id']);

        // Vérifier le titre
        $this->assertSame(
            'Todo à récupérer',
            $result['title']
        );
    }

    // Test de mise à jour d'un Todo inexistant
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

        // Un Todo inexistant doit retourner 404
        $this->assertResponseStatusCodeSame(404);

        $data = json_decode(
            $client->getResponse()->getContent(),
            true
        );

        // Vérifier le message d'erreur
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

        // Supprimer le Todo
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
