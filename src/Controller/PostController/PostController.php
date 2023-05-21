<?php

namespace App\Controller\PostController;

use App\Http\TransformJsonBody;
use App\Repository\PostsRepository\PostRepository;
use App\Repository\UsersRepository\UserRepository;
use App\Service\PostService\CreatePostService;
use App\Service\PostService\UpdatePostService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    #[Route('/api/posts/create', name:'createPost', methods: ['POST'])]
    public function createPost(EntityManagerInterface $entityManager, UserRepository $userRepository, Request $request): JsonResponse
    {
        try {

            $request = TransformJsonBody::handleRequest($request);

            $user = $userRepository->find($request->get('user_uuid'));

            if(!$user){

                $data = [
                    'status' => 401,
                    'message' => 'User Not Found'
                ];

                return $this->json($data, 401);
            }

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
    public function updatePost(EntityManagerInterface $entityManager,PostRepository $postsRepository, Request $request, string $post_uuid): JsonResponse
    {
        try {
            
            $request = TransformJsonBody::handleRequest($request);

            $post = $postsRepository->find($post_uuid);

            if(!$post){
                $data = [
                    'status' => 404,
                    'message' => 'Post not found'
                ];
    
                return $this->json($data);
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

    #[Route('/api/posts/delete/{post_uuid}', name: 'deletePostById', methods: ['DELETE'])]
    public function deletePostById(EntityManagerInterface $entityManager, PostRepository $postsRepository, string $post_uuid): JsonResponse
    {
        $post = $postsRepository->find($post_uuid);

        if(!$post){
            $data = [
                'status' => 404,
                'message' => 'Post not found'
            ];

            return $this->json($data, 404);
        }

        $entityManager->remove($post);
        $entityManager->flush();

        $data = [
            "status" => 200,
            "message" => "Post deleted successfully"
        ];

        return $this->json($data);
    }

    #[Route('/api/posts/get/all', name: 'getAllPosts', methods: ['GET'])]
    public function getAllPosts(PostRepository $postsRepository): JsonResponse
    {
        $posts = $postsRepository->findAll();

        return $this->json($posts, 200);
    }

    #[Route('/api/posts/get/{post_uuid}', name: 'getOnePost', methods: ['GET'])]
    public function getPostById(PostRepository $postsRepository, string $post_uuid): JsonResponse
    {
        $post = $postsRepository->find($post_uuid);

        if(!$post){
            $data = [
                'status' => 401,
                'message' => 'Post not found'
            ];

            return $this->json($data);
        }

        return $this->json($post);
    }

    #[Route('/api/posts/get/profile/{user_uuid}', name: 'getPostByUser', methods: ['GET'])]
    public function getAllPostsByUser(UserRepository $userRepository, string $user_uuid): JsonResponse
    {
        $user = $userRepository->find($user_uuid);

        if(!$user){
            
            $data = [
                'status' => 401,
                'message' => 'User not found'
            ];

            return $this->json($data);
        }

        $posts = $user->getPosts()->toArray();

        return $this->json($posts);
    }
}