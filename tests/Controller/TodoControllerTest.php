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
}
