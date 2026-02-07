<?php

namespace App\Controller;

use App\Dto\Request\LoginRequestDto;
use App\Dto\Response\LoginResponse;
use App\Dto\Response\UserResponse;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends DefaultController
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtManager
    ) {}

    #[Route('/auth/login', name: 'auth_login', methods: ['POST'])]
    #[OA\Post(
        path: '/api/auth/login',
        description: 'User authentication',
        summary: 'Login with email and password'
    )]
    #[OA\RequestBody(content: new Model(type: LoginRequestDto::class))]
    #[OA\Response(
        response: 200,
        description: 'JWT token and user data',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGc...'),
                new OA\Property(
                    property: 'user',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'email', type: 'string', example: 'test@example.com'),
                        new OA\Property(property: 'roles', type: 'array', items: new OA\Items(type: 'string'))
                    ],
                    type: 'object'
                )
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'Invalid credentials')]
    #[OA\Response(response: 422, description: 'Validation error')]
    #[OA\Tag(name: 'Authentication')]
    public function login(#[MapRequestPayload] LoginRequestDto $loginRequest): JsonResponse
    {
        $user = $this->userRepository->findOneBy(['email' => $loginRequest->email]);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $loginRequest->password)) {
            return $this->json(['error' => 'Invalid credentials'], 401);
        }

        $token = $this->jwtManager->create($user);

        $response = new LoginResponse();
        $response->token = $token;
        $response->user = UserResponse::fromEntity($user);

        return $this->response($response);
    }
}
