<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class HealthController extends DefaultController
{
    #[Route('/health', name: 'health', methods: ['GET'])]
    #[OA\Get(
        path: '/api/health',
        description: 'Health check endpoint',
        summary: 'Check API health status'
    )]
    #[OA\Response(
        response: 200,
        description: 'API is healthy',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'status', type: 'string', example: 'ok'),
                new OA\Property(property: 'timestamp', type: 'integer', example: 1770235649)
            ]
        )
    )]
    #[OA\Tag(name: 'Health')]
    public function health(): JsonResponse
    {
        return $this->json([
            'status' => 'ok',
            'timestamp' => time()
        ]);
    }
}
