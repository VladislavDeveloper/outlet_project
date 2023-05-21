<?php

namespace App\Controller\LikeController;

use App\Entity\Like\Like;
use App\Repository\LikeRepository\LikeRepository;
use App\Repository\PostsRepository\PostRepository;
use App\Repository\UsersRepository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    #[Route('/api/post/like/{post_uuid}', methods: ['POST'])]
    public function addLike(EntityManagerInterface $entityManager, PostRepository $postRepository, UserRepository $userRepository, LikeRepository $likeRepository, Request $request, string $post_uuid): JsonResponse
    {

        try {
            $request = $this->transformJsonBody($request);

            $user = $userRepository->find($request->get('user_uuid'));

            $post = $postRepository->find($post_uuid);

            $like = $likeRepository->findBy(['post' => $post_uuid, 'author' => $user->getUser_uuid()]);

            if($like){

                throw new Exception("You already liked this post");

            }

            $like = new Like();

            $like->setAuthor($user);

            $like->setPost($post);

            $post->addLike($like);

            $entityManager->persist($like);

            $entityManager->flush();

            $data = [
                "status" => 200,
                "message" => "Like saved"
            ];

            return $this->response($data);

        } catch (Exception $e) {
            $data = [
                "status" => 500,
                "message" => $e->getMessage()
            ];

            return $this->response($data);
        }
    }

    #[Route('/api/post/{post_uuid}/likes')]
    public function getLikesToPost(PostRepository $postRepository, string $post_uuid): JsonResponse
    {
        $post = $postRepository->find($post_uuid);

        if(!$post){
            $data = [
                "status" => 401,
                "message" => "Likes not found"
            ];
            return $this->response($data);
        }

        $likes = $post->getLikes()->toArray();

        return $this->response($likes);
    }

    #[Route('/api/post/{post_uuid}/like/remove')]
    public function deleteLike(EntityManagerInterface $entityManager, PostRepository $postRepository, UserRepository $userRepository, LikeRepository $likeRepository, Request $request, string $post_uuid): JsonResponse
    {

        try{

            $request = $this->transformJsonBody($request);

            $user = $userRepository->find($request->get('user_uuid'));

            $post = $postRepository->find($post_uuid);

            $like = $likeRepository->findOneBy(['post' => $post_uuid, 'author' => $user->getUser_uuid()]);

            if(!$like){

                $data = [
                    "status" => 401,
                    "message" => "Like not found",
                ];

                return $this->response($data);
            }

            $entityManager->remove($like);

            $post->removeLike($like);

            $entityManager->flush();

            $data = [
                'status' => 200,
                'message' => 'like was remove'
            ];

            return $this->response($data);


        }catch(Exception $e){
            $data = [
                "status" => 500,
                "message" => $e->getMessage()
            ];

            return $this->response($data);
        }
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