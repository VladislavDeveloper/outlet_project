<?php

namespace App\Service\PostService;

use App\Entity\Post\Post;
use App\Entity\User\User;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class CreatePostService
{
    static public function handler(Request $request, User $author): Post
    {
        try {
            $post = new Post();

            $post->setAuthor($author);

            $post->setTitle($request->get('title'));

            $post->setBody($request->get('body'));

            $post->setPostImageId($request->get('post_image_id'));

            $date_of_create = new DateTime();

            $post->setDateOfCreate($date_of_create);

            $post->setLikes([]);

            $post->setDislikes([]);
            
        } catch (\Throwable $th) {
            Throw new Exception('wrong data');
        }
        
        return $post;
        
    }
}
