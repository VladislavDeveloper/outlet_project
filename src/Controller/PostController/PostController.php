<?php

namespace App\Controller\PostController;

use App\PostsRepository\PostRepository;
use App\Repository\UsersRepository\UserRepository;
use App\Service\PostService\CreatePostService;
use App\Service\PostService\UpdatePostService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/api/posts/add', methods: ['POST'])]
    public function createPost(EntityManagerInterface $entityManager, UserRepository $userRepository, Request $request): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);

            $user = $userRepository->find($request->get('user_uuid'));

            $post = CreatePostService::handler($request, $user);

            $entityManager->persist($post);
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

    #[Route('/api/posts/update/{post_uuid}', name: 'updatePostById', methods: ['POST'])]
    public function updatePost(EntityManagerInterface $entityManager, UserRepository $userRepository, PostRepository $postsRepository, Request $request, string $post_uuid): JsonResponse
    {
        try {
            
            $request = $this->transformJsonBody($request);

            $post = $postsRepository->find($post_uuid);

            if(!$post){
                $data = [
                    'status' => 404,
                    'message' => 'Post not found'
                ];
    
                return $this->response($data, 404);
            }

            $post = UpdatePostService::handler($request, $post);


            $entityManager->persist($post);
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

    #[Route('/api/posts/get/all')]
    public function getAllPosts(PostRepository $postsRepository): JsonResponse
    {
        $posts = $postsRepository->findAll();

        return $this->response($posts);
    }

    #[Route('/api/posts/delete/{post_uuid}', name: 'deletePostById', methods: ['GET'])]
    public function deletePostById(EntityManagerInterface $entityManager, PostRepository $postsRepository, string $post_uuid): JsonResponse
    {
        $post = $postsRepository->find($post_uuid);

        if(!$post){
            $data = [
                'status' => 404,
                'message' => 'Post not found'
            ];

            return $this->response($data, 404);
        }

        $entityManager->remove($post);
        $entityManager->flush();

        $data = [
            "status" => 200,
            "message" => "Post deleted successfully"
        ];

        return $this->response($data);
    }

    #[Route('/api/posts/get/{post_uuid}', name: 'getOnePost', methods: ['GET'])]
    public function getPostById(PostRepository $postsRepository, string $post_uuid): JsonResponse
    {
        $post = $postsRepository->find($post_uuid);

        return $this->response($post);
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