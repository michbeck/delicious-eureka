<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class CollectionApiControllerTest extends WebTestCase
{
    public function testListSuccess(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/collections/fruit');

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertIsArray($data);
    }

    public function testListInvalidType(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/v1/collections/invalidtype');

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    public function testAddSuccess(): void
    {
        $client = static::createClient();
        $payload = [
            'id' => 123,
            'name' => uniqid('test_fruit_', true),
            'quantity' => 2,
            'unit' => 'kg',
        ];
        $client->request(
            'POST',
            '/api/v1/collections/fruit',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseFormatSame('json');
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('uuid', $data);
    }

    public function testAddInvalidPayload(): void
    {
        $client = static::createClient();
        $payload = [
            'id' => 123,
            'name' => '',
            'quantity' => null,
            'unit' => 'kg',
        ];
        $client->request(
            'POST',
            '/api/v1/collections/fruit',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($payload)
        );

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertSame($data['message'], 'Invalid request');
    }
}

