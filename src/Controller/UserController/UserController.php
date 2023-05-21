<?php

namespace App\Controller\UserController;

use App\Repository\UsersRepository\UserRepository;
use App\Service\UserService\CreateUserService;
use App\Service\UserService\UpdateUserService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/api/profile/create', name: 'add_user')]
    public function createUser(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, Request $request): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);

            $user = CreateUserService::handler($request, $passwordHasher);

            $entityManager->persist($user);

            $entityManager->flush();

            $data = [
                'status' => 200,
                'Message' => 'Profile successfully saved'
            ];

            return $this->json($data);

        } catch (Exception $e) {
            $data = [
                'status' => 404,
                'Message' => 'Profile not saved',
                'error' => $e->getMessage()
            ];
            return $this->json($data);
        }
    }

    #[Route('/api/profiles/get/all', name: 'getAllUsers', methods: ['GET'])]
    public function getAllUsers(UserRepository $usersRepository): JsonResponse
    {
        $data = $usersRepository->findAll();
        return $this->json($data);
    }

    #[Route('/api/profile/get/{user_uuid}')]
    public function getUserById(UserRepository $usersRepository, string $user_uuid): JsonResponse
    {
        $data = $usersRepository->find($user_uuid);

        if(!$data){
            $data = [
                'status' => 404,
                'message' => 'User not found'
            ];

            return $this->json($data, 404);
        }

        return $this->json($data);
    }

    #[Route('/api/update/profile/{user_uuid}', name: 'updateProfile', methods: ['POST'])]
    public function updateUser(UserRepository $usersRepository, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, string $user_uuid): JsonResponse
    {
        $user = $usersRepository->find($user_uuid);

        if(!$user){
            $data = [
                'status' => 404,
                'message' => 'User not found'
            ];
            return $this->json($data, 404);
        }

        try {

            $request = $this->transformJsonBody($request);

            if(!$request){
                $data = [
                    'status' => 404,
                    'message' => 'Nothing to update'
                ];
                return $this->json($data, 404);
            }

            $user = UpdateUserService::handler($request, $user, $passwordHasher);

            $entityManager->persist($user);

            $entityManager->flush();

            $data = [
                'status' => 200,
                'Message' => 'Profile successfully saved'
            ];

            return $this->json($data);

        } catch (Exception $e) {
            $data = [
                'status' => 404,
                'Message' => 'Post not saved',
                'error' => $e->getMessage()
            ];
            return $this->json($data);
        }
    }

    #[Route('/api/delete/profile/{user_uuid}', name: 'delete_account')]
    public function deleteUserByUuid(EntityManagerInterface $entityManager, UserRepository $usersRepository, string $user_uuid): JsonResponse
    {
        $data = $usersRepository->find($user_uuid);

        if(!$data){
            $data = [
                'status' => 404,
                'message' => 'User not found'
            ];

            return $this->json($data, 404);
        }

        $entityManager->remove($data);
        $entityManager->flush();

        $data = [
            "status" => 200,
            "message" => "User deleted successfully"
        ];


        return $this->json($data);
    }

    #[Route('/api/test')]
    public function test(): JsonResponse
    {
        return $this->json([
            'status' => 200
        ]);
    }

    protected function transformJsonBody(Request $request): Request
    {
        $data = json_decode($request->getContent(), true);

        if($data === null){
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}