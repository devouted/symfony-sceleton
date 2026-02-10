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

    public function testGetUserNotFound(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'password' => 'test123'
        ]));
        $token = json_decode($client->getResponse()->getContent(), true)['token'];

        $client->request('GET', '/api/admin/users/99999', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertResponseStatusCodeSame(404);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('code', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertArrayHasKey('type', $data);
        $this->assertEquals(404, $data['code']);
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

    public function testCreateUserValidationInvalidEmail(): void
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
            'email' => 'notanemail',
            'password' => 'password123',
            'roles' => ['ROLE_USER']
        ]));
        $this->assertResponseStatusCodeSame(422);
    }

    public function testCreateUserValidationPasswordTooShort(): void
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
            'email' => 'newuser@example.com',
            'password' => '12345',
            'roles' => ['ROLE_USER']
        ]));
        $this->assertResponseStatusCodeSame(422);
    }

    public function testCreateUserValidationInvalidRole(): void
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
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'roles' => ['INVALID_ROLE']
        ]));
        $this->assertResponseStatusCodeSame(422);
    }

    public function testUpdateUserNotFound(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'password' => 'test123'
        ]));
        $token = json_decode($client->getResponse()->getContent(), true)['token'];

        $client->request('PUT', '/api/admin/users/99999', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'roles' => ['ROLE_USER']
        ]));
        $this->assertResponseStatusCodeSame(404);
    }

    public function testUpdateUserValidationInvalidEmail(): void
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
            'email' => 'notanemail'
        ]));
        $this->assertResponseStatusCodeSame(422);
    }

    public function testDeleteUser(): void
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
            'email' => 'todelete' . time() . '@example.com',
            'password' => 'password123',
            'roles' => ['ROLE_USER']
        ]));
        $userId = json_decode($client->getResponse()->getContent(), true)['id'];

        $client->request('DELETE', '/api/admin/users/' . $userId, [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertResponseStatusCodeSame(204);
    }

    public function testDeleteUserNotFound(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'password' => 'test123'
        ]));
        $token = json_decode($client->getResponse()->getContent(), true)['token'];

        $client->request('DELETE', '/api/admin/users/99999', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token
        ]);
        $this->assertResponseStatusCodeSame(404);
    }

    public function testAssignRolesNotFound(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'test@example.com',
            'password' => 'test123'
        ]));
        $token = json_decode($client->getResponse()->getContent(), true)['token'];

        $client->request('POST', '/api/admin/users/99999/roles', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'roles' => ['ROLE_USER']
        ]));
        $this->assertResponseStatusCodeSame(404);
    }

    public function testAssignRolesValidationInvalidRole(): void
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
            'roles' => ['INVALID_ROLE']
        ]));
        $this->assertResponseStatusCodeSame(422);
    }

    public function testAdminEndpointsForbiddenForNonAdmin(): void
    {
        $client = static::createClient();
        $client->request('POST', '/api/admin/users', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'email' => 'regularuser' . time() . '@example.com',
            'password' => 'password123',
            'roles' => ['ROLE_USER']
        ]));
        $userId = json_decode($client->getResponse()->getContent(), true)['id'];

        $client->request('POST', '/api/auth/login', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'email' => 'regularuser' . ($userId) . '@example.com',
            'password' => 'password123'
        ]));
        $regularToken = json_decode($client->getResponse()->getContent(), true)['token'];

        $client->request('GET', '/api/admin/users', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $regularToken
        ]);
        $this->assertResponseStatusCodeSame(403);
    }
}
