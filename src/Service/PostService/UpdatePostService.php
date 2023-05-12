<?php

namespace App\Service\PostService;

use App\Entity\Post\Post;
use App\Entity\User\User;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class UpdatePostService
{
    static public function handler(Request $request, Post $post): Post
    {
        try {

            if($request->get('title')){
                $post->setTitle($request->get('title'));
            }
            
            if($request->get('body')){
                $post->setBody($request->get('body'));
            }
            
            if($request->get('post_image_id')){
                $post->setPostImageId($request->get('post_image_id'));
            }

            return $post;

        } catch (\Throwable $th) {
            Throw new Exception('wrong data');
        }
    }
}
