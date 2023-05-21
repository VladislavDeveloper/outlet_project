<?php

namespace App\Service\PostService;

use App\Entity\Post\Post;
use Exception;
use Symfony\Component\HttpFoundation\Request;

class UpdatePostService
{
    static public function handler(Request $request, Post $post): Post
    {
        $uploadFile = $request->files->get('post_image');

        try {

            if($request->get('title')){
                $post->setTitle($request->get('title'));
            }
            
            if($request->get('body')){
                $post->setBody($request->get('body'));
            }
            
            if($uploadFile){
                $post->setImageFile($uploadFile);
            }

            return $post;

        } catch (\Throwable $th) {
            Throw new Exception('wrong data');
        }
    }
}
