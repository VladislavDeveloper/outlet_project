<?php

namespace App\Controller\UserController;

use App\Entity\User\User;
use App\Repository\UsersRepository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/addUser', name: 'add_user')]
    public function createUser(EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);

            $user = new User();
            $user->setUsername($request->get('username'));

            //Хэширование пароля

            $passwordHash = hash('sha256', $request->get('password'));

            $user->setPassword($passwordHash);

            $user->setFirst_name($request->get('first_name'));
            $user->setLast_name($request->get('last_name'));
            $user->setUser_photo_id($request->get('user_photo_id'));

            //Конвертируем строковое представление date_of_birth в  объект datetime
            $date_of_birth = DateTime::createFromFormat('d/m/Y', $request->get('date_of_birth'));

            $user->setDate_of_birth($date_of_birth);
            
            $date_of_create = new DateTime();
            
            $user->setDate_of_create($date_of_create);
            $user->setGender($request->get('gender'));

            $entityManager->persist($user);
            $entityManager->flush();

            $data = [
                'status' => 200,
                'Message' => 'Post successfully saved'
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

    #[Route('/getusers')]
    public function getAllUsers(UserRepository $usersRepository): JsonResponse
    {
        $data = $usersRepository->findAll();
        return $this->response($data);
    }

    #[Route('/getuser/{user_uuid}')]
    public function getUserById(UserRepository $usersRepository, string $user_uuid): JsonResponse
    {
        $data = $usersRepository->find($user_uuid);

        if(!$data){
            $data = [
                'status' => 404,
                'message' => 'User not found'
            ];

            return $this->response($data, 404);
        }

        return $this->response($data);
    }

    #[Route('/test')]
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

    protected function response($data, $status = 200, $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }
}