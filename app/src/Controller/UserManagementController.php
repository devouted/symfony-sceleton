<?php

namespace App\Controller;

use App\Dto\Request\AssignRolesRequest;
use App\Dto\Request\CreateUserRequest;
use App\Dto\Request\UpdateUserRequest;
use App\Dto\Response\UserResponse;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/users')]
#[IsGranted('ROLE_ADMIN')]
class UserManagementController extends DefaultController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    #[Route('', name: 'admin_users_list', methods: ['GET'])]
    #[OA\Get(
        path: '/api/admin/users',
        summary: 'List all users',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Response(response: 200, description: 'Users list')]
    #[OA\Tag(name: 'User Management')]
    public function list(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        return $this->json(array_map(fn($u) => UserResponse::fromEntity($u), $users));
    }

    #[Route('/{id}', name: 'admin_users_get', methods: ['GET'])]
    #[OA\Get(
        path: '/api/admin/users/{id}',
        summary: 'Get user details',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Response(response: 200, description: 'User details')]
    #[OA\Response(response: 404, description: 'User not found')]
    #[OA\Tag(name: 'User Management')]
    public function get(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }
        return $this->response(UserResponse::fromEntity($user));
    }

    #[Route('', name: 'admin_users_create', methods: ['POST'])]
    #[OA\Post(
        path: '/api/admin/users',
        summary: 'Create new user',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Response(response: 201, description: 'User created')]
    #[OA\Response(response: 400, description: 'Validation error')]
    #[OA\Tag(name: 'User Management')]
    public function create(#[MapRequestPayload] CreateUserRequest $request): JsonResponse
    {
        $user = new User();
        $user->setEmail($request->email);
        $user->setRoles($request->roles);
        $user->setPassword($this->passwordHasher->hashPassword($user, $request->password));

        $this->em->persist($user);
        $this->em->flush();

        return $this->response(UserResponse::fromEntity($user));
    }

    #[Route('/{id}', name: 'admin_users_update', methods: ['PUT'])]
    #[OA\Put(
        path: '/api/admin/users/{id}',
        summary: 'Update user',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Response(response: 200, description: 'User updated')]
    #[OA\Response(response: 404, description: 'User not found')]
    #[OA\Tag(name: 'User Management')]
    public function update(int $id, #[MapRequestPayload] UpdateUserRequest $request): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        if ($request->email) $user->setEmail($request->email);
        if ($request->roles) $user->setRoles($request->roles);
        if ($request->password) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $request->password));
        }

        $this->em->flush();
        return $this->response(UserResponse::fromEntity($user));
    }

    #[Route('/{id}', name: 'admin_users_delete', methods: ['DELETE'])]
    #[OA\Delete(
        path: '/api/admin/users/{id}',
        summary: 'Soft delete user',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Response(response: 204, description: 'User deleted')]
    #[OA\Response(response: 404, description: 'User not found')]
    #[OA\Tag(name: 'User Management')]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $user->setDeletedAt(new \DateTimeImmutable());
        $this->em->flush();

        return $this->json(null, 204);
    }

    #[Route('/{id}/roles', name: 'admin_users_roles', methods: ['POST'])]
    #[OA\Post(
        path: '/api/admin/users/{id}/roles',
        summary: 'Assign roles to user',
        security: [['bearerAuth' => []]]
    )]
    #[OA\Response(response: 200, description: 'Roles assigned')]
    #[OA\Response(response: 404, description: 'User not found')]
    #[OA\Tag(name: 'User Management')]
    public function assignRoles(int $id, #[MapRequestPayload] AssignRolesRequest $request): JsonResponse
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        }

        $user->setRoles($request->roles);
        $this->em->flush();

        return $this->response(UserResponse::fromEntity($user));
    }
}
