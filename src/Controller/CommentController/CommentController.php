<?php

namespace App\Controller\CommentController;

use App\Entity\Comment\Comment;
use App\PostsRepository\PostRepository;
use App\Repository\CommentsRepository;
use App\Repository\UsersRepository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/api/posts/comments/add')]
    public function addComment(EntityManagerInterface $entityManager, PostRepository $postRepository, UserRepository $usersRepository, Request $request): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);

            $user = $usersRepository->find($request->get('user_uuid'));

            $post = $postRepository->find($request->get('post_uuid'));

            $comment = new Comment();

            $comment->setAuthor($user);

            $comment->setPost($post);

            $comment->setText($request->get('comment_text'));

            $post->addComment($comment);

            $entityManager->persist($comment);

            $entityManager->flush();

            $data = [
                'status' => 200,
                'Message' => 'Your comment saved'
            ];

            return $this->json($data);

        } catch (Exception $e) {
            $data = [
                'status' => 404,
                'Message' => 'Comment not saved',
                'error' => $e->getMessage()
            ];
            return $this->json($data);
        }
        
    }

    #[Route('/api/posts/comment/update')]
    public function updateComment(CommentsRepository $commentsRepository, EntityManagerInterface $entityManager, Request $request): JsonResponse
    {
        try {
            $request = $this->transformJsonBody($request);

            $comment = $commentsRepository->find($request->get('comment_uuid'));

            $post = $comment->getPost();

            if($request->get('comment_text')){
                $comment->setText($request->get('comment_text'));
            }

            $post->addComment($comment);

            $entityManager->persist($comment);

            $entityManager->flush();

            $data = [
                'status' => 200,
                'Message' => 'Your comment updated'
            ];

            return $this->json($data);

        } catch (\Throwable $e) {
            $data = [
                'status' => 404,
                'Message' => 'Comment not saved',
                'error' => $e->getMessage()
            ];
            return $this->json($data);
        }
    }

    #[Route('/api/posts/get/comments/{post_uuid}')]
    public function getCommentsToPost(PostRepository $postRepository, string $post_uuid)
    {
        $post = $postRepository->find($post_uuid);

        $comments = $post->getComments();

        return $this->response($comments->toArray());

    }

    #[Route('/api/posts/comment/delete/{comment_uuid}')]
    public function removeCommentById(CommentsRepository $commentsRepository, EntityManagerInterface $entityManager, string $comment_uuid): JsonResponse
    {
        $comment = $commentsRepository->find($comment_uuid);

        if(!$comment){
            
            $data = [
                'status' => 200,
                'Message' => 'Comment not found'
            ];

            return $this->json($data);
        }

        $post = $comment->getPost();

        $post->removeComment($comment);

        $entityManager->remove($comment);
        
        $entityManager->flush();

        $data = [
            'status' => 200,
            'message' => 'Comment deleted'
        ];

        return $this->response($data);

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