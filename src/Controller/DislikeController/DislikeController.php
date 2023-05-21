<?php

namespace App\Controller\DislikeController;

use App\Entity\Dislike\Dislike;

use App\Repository\DislikeRepository\DislikeRepository;
use App\Repository\PostsRepository\PostRepository;
use App\Repository\UsersRepository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DislikeController extends AbstractController
{
    #[Route('/api/post/dislike/{post_uuid}')]
    public function addDislike(EntityManagerInterface $entityManager, PostRepository $postRepository, UserRepository $userRepository, DislikeRepository $dislikeRepository, Request $request, string $post_uuid): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);

            $user = $userRepository->find($request->get('user_uuid'));

            $post = $postRepository->find($post_uuid);

            $dislike = $dislikeRepository->findBy(['post' => $post_uuid, 'author' => $user->getUser_uuid()]);

            if($dislike){

                throw new Exception("You already disliked this post");

            }

            $dislike = new Dislike();

            $dislike->setAuthor($user);

            $dislike->setPost($post);

            $post->addDislike($dislike);

            $entityManager->persist($dislike);

            $entityManager->flush();

            $data = [
                "status" => 200,
                "message" => "Dislike saved"
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

    #[Route('/api/post/dislike/remove/{post_uuid}', methods: ['DELETE'])]
    public function deleteDislike(EntityManagerInterface $entityManager, PostRepository $postRepository, UserRepository $userRepository, DislikeRepository $dislikeRepository, Request $request, string $post_uuid): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);

            $user = $userRepository->find($request->get('user_uuid'));

            $post = $postRepository->find($post_uuid);

            $dislike = $dislikeRepository->findOneBy(['post' => $post_uuid, 'author' => $user->getUser_uuid()]);

            if(!$dislike){

                throw new Exception("Dislike not found");

            }

            $entityManager->remove($dislike);

            $post->removeDislike($dislike);

            $entityManager->flush();


            $data = [
                'status' => 200,
                'message' => 'Dislike was remove'
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