<?php

namespace App\Controller;

use App\Dto\Response\AdminTestResponse;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[Route('/admin/test', name: 'admin_test', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    #[OA\Get(
        path: '/api/admin/test',
        description: 'Test endpoint secured with ROLE_ADMIN',
        summary: 'Admin test endpoint (requires ROLE_ADMIN)',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Response(
        response: 200,
        description: 'Success response',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Admin access granted'),
                new OA\Property(property: 'user', type: 'string', example: 'admin@example.com')
            ]
        )
    )]
    #[OA\Response(response: 401, description: 'Unauthorized - missing or invalid token')]
    #[OA\Response(response: 403, description: 'Forbidden - insufficient permissions')]
    #[OA\Tag(name: 'Admin')]
    public function test(): JsonResponse
    {
        $response = new AdminTestResponse();
        $response->message = 'Admin access granted';
        $response->user = $this->getUser()->getUserIdentifier();
        return $this->json($response);
    }
}
