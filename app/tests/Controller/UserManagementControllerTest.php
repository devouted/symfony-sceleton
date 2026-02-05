<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserManagementControllerTest extends WebTestCase
{
    public function testListUsers(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'password' => 'test123'
        ]));
        $token = json_decode($client->getResponse()->getContent(), true)['token'];

        $client->request('GET', '/api/admin/users', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertResponseIsSuccessful();
    }

    public function testCreateUser(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'password' => 'test123'
        ]));
        $token = json_decode($client->getResponse()->getContent(), true)['token'];

        $client->request('POST', '/api/admin/users', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => 'newuser' . time() . '@example.com',
            'password' => 'password123',
            'roles' => ['ROLE_USER']
        ]));
        $this->assertResponseStatusCodeSame(201);
    }

    public function testGetUser(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'password' => 'test123'
        ]));
        $token = json_decode($client->getResponse()->getContent(), true)['token'];

        $client->request('GET', '/api/admin/users/1', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertResponseIsSuccessful();
    }

    public function testUpdateUser(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'password' => 'test123'
        ]));
        $token = json_decode($client->getResponse()->getContent(), true)['token'];

        $client->request('PUT', '/api/admin/users/1', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'roles' => ['ROLE_USER', 'ROLE_ADMIN']
        ]));
        $this->assertResponseIsSuccessful();
    }

    public function testAssignRoles(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'password' => 'test123'
        ]));
        $token = json_decode($client->getResponse()->getContent(), true)['token'];

        $client->request('POST', '/api/admin/users/1/roles', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'roles' => ['ROLE_USER', 'ROLE_ADMIN']
        ]));
        $this->assertResponseIsSuccessful();
    }
}
