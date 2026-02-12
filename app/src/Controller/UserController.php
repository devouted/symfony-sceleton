<?php

namespace App\Controller;

use App\Dto\Response\UserResponse;
use Nelmio\ApiDocBundle\Attribute\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends DefaultController
{
    #[Route('/me', name: 'user_me', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[OA\Get(
        path: '/api/me',
        description: 'Get current user information',
        summary: 'Get authenticated user data'
    )]
    #[OA\Response(
        response: 200,
        description: 'User data',
        content: new Model(type: UserResponse::class)
    )]
    #[OA\Response(response: 401, description: 'Unauthorized')]
    #[OA\Tag(name: 'User')]
    public function me(): JsonResponse
    {
        $user = $this->getUser();
        return $this->response(UserResponse::fromEntity($user));
    }
}
